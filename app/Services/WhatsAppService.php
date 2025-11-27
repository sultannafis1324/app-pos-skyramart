<?php

namespace App\Services;

use App\Models\ReceiptTemplate;
use App\Models\Sale;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $instanceId;
    protected $token;
    protected $apiUrl;

    public function __construct()
    {
        $this->instanceId = trim(config('services.green_api.instance_id'));
        $this->token = trim(config('services.green_api.token'));
        $this->apiUrl = rtrim(config('services.green_api.api_url'), '/');
    }

    /**
     * ✅ Send receipt dengan TEMPLATE
     */
    public function sendReceipt($phoneNumber, Sale $sale, $pdfPath = null, $fileName = null)
    {
        $formattedPhone = $this->formatPhoneNumber($phoneNumber);

        try {
            Log::info('📤 Starting WhatsApp receipt (Template mode)', [
                'phone' => $formattedPhone,
                'sale_id' => $sale->id
            ]);

            // 1️⃣ Get WhatsApp template
            $template = ReceiptTemplate::getActive('whatsapp');
            if (!$template) {
                throw new \Exception('WhatsApp template not found');
            }

            // 2️⃣ Build message menggunakan template
            $message = $this->buildMessageFromTemplate($sale, $template);

            // 3️⃣ Send message
            $textResult = $this->sendTextMessage($formattedPhone, $message);
            if ($textResult) {
                Log::info('✅ Template message sent');
            }

            sleep(1);

            // 4️⃣ Upload PDF & send link
            if ($pdfPath && file_exists($pdfPath)) {
                $cloudinary = new CloudinaryService();
                $uploadResult = $cloudinary->uploadReceipt($pdfPath, $fileName, 168); // 7 days

                if ($uploadResult && isset($uploadResult['url'])) {
                    // ✅ Build PDF message dari template
                    $pdfData = [
                        'pdf_link' => $uploadResult['url'],
                        'customer_name' => $sale->customer->customer_name ?? 'Walk-in Customer',
                    ];
                    
                    $pdfMessage = $template->replaceVariables(
                        $template->notes_text ?? "📄 *Download Your Receipt (PDF)*\n\nClick link below:\n{pdf_link}\n\n_Link valid for 7 days_",
                        $pdfData
                    );

                    sleep(2); // Delay sebelum kirim link
                    
                    $linkSent = $this->sendTextMessage($formattedPhone, $pdfMessage);
                    
                    if ($linkSent) {
                        Log::info('✅ PDF link sent successfully', ['url' => $uploadResult['url']]);
                    } else {
                        Log::warning('⚠️ Failed to send PDF link via WhatsApp');
                    }
                } else {
                    Log::warning('⚠️ Cloudinary upload failed');
                }

                // Cleanup local PDF
                @unlink($pdfPath);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('❌ WhatsApp receipt error', [
                'error' => $e->getMessage(),
                'phone' => $formattedPhone
            ]);
            return false;
        }
    }

    /**
     * ✅ Build message dari template
     */
    protected function buildMessageFromTemplate(Sale $sale, ReceiptTemplate $template)
    {
        $payment = $sale->payments->first();

        // Data untuk replace variables
        $data = [
            'customer_name' => $sale->customer->customer_name ?? 'Walk-in Customer',
            'transaction_number' => $sale->transaction_number,
            'date' => $sale->sale_date->format('d M Y'),
            'time' => $sale->sale_date->format('H:i'),
            'cashier_name' => $sale->user->name,
            'subtotal' => $sale->subtotal,
            'discount' => $sale->discount,
            'discount_percentage' => $sale->discount_percentage,
            'tax' => $sale->tax,
            'total' => $sale->total_price,
            'payment_method' => $payment ? strtoupper($payment->payment_method) : 'CASH',
            'payment_channel' => $payment->payment_channel ?? '-',
        ];

        // Build message
        $message = "";

        // Header
        if ($template->header_text) {
            $message .= $template->replaceVariables($template->header_text, $data) . "\n\n";
        }

        // Greeting
        if ($template->greeting_text) {
            $message .= $template->replaceVariables($template->greeting_text, $data) . "\n\n";
        }

        // Transaction Section
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        if ($template->transaction_section_title) {
            $message .= $template->replaceVariables($template->transaction_section_title, $data) . "\n";
        }
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "🧾 Receipt No: *{$sale->transaction_number}*\n";
        $message .= "📅 Date: {$sale->sale_date->format('d M Y, H:i')}\n";
        $message .= "👤 Cashier: {$sale->user->name}\n\n";

        // Items Section
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        if ($template->items_section_title) {
            $message .= $template->replaceVariables($template->items_section_title, $data) . "\n";
        }
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";

        foreach ($sale->saleDetails as $index => $detail) {
            $itemNum = $index + 1;
            $message .= "{$itemNum}. {$detail->product_name}\n";
            $message .= "   {$detail->quantity} x Rp" . number_format($detail->unit_price, 0, ',', '.') . 
                        " = Rp" . number_format($detail->subtotal, 0, ',', '.') . "\n";

            if ($detail->price_promotion_id || $detail->quantity_promotion_id) {
                if ($detail->price_promotion_id) {
                    $message .= "   💰 Discount: -Rp" . number_format($detail->price_discount_amount, 0, ',', '.') . "\n";
                }
                if ($detail->quantity_promotion_id && $detail->free_quantity > 0) {
                    $message .= "   🎁 Bonus: +{$detail->free_quantity} FREE\n";
                }
            }
            $message .= "\n";
        }

        // Payment Section
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        if ($template->payment_section_title) {
            $message .= $template->replaceVariables($template->payment_section_title, $data) . "\n";
        }
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "Subtotal: Rp" . number_format($sale->subtotal, 0, ',', '.') . "\n";

        if ($sale->discount > 0) {
            $message .= "Discount ({$sale->discount_percentage}%): -Rp" . number_format($sale->discount, 0, ',', '.') . "\n";
        }

        if ($sale->tax > 0) {
            $message .= "Tax: Rp" . number_format($sale->tax, 0, ',', '.') . "\n";
        }

        $message .= "*TOTAL: Rp" . number_format($sale->total_price, 0, ',', '.') . "*\n\n";

        if ($payment) {
            $message .= "Payment Method: *" . strtoupper($payment->payment_method) . "*\n";
            if ($payment->payment_channel) {
                $message .= "Channel: {$payment->payment_channel}\n";
            }
        }

        // Contact Info
        $message .= "\n━━━━━━━━━━━━━━━━━━━━\n\n";
        if ($template->contact_info) {
            $message .= $template->replaceVariables($template->contact_info, $data) . "\n\n";
        }

        // Footer
        if ($template->footer_text) {
            $message .= $template->replaceVariables($template->footer_text, $data) . "\n\n";
        }

        // Store Branding
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        if ($template->store_branding) {
            $message .= $template->replaceVariables($template->store_branding, $data);
        } else {
            $message .= "💚 *SkyraMart* - Your Trusted Store"; // Fallback
        }

        return $message;
    }

    /**
     * Send text message (untuk receipt & welcome message)
     */
    public function sendTextMessage($phoneNumber, $message)
    {
        try {
            $url = "{$this->apiUrl}/waInstance{$this->instanceId}/sendMessage/{$this->token}";
            
            $response = Http::timeout(30)->post($url, [
                'chatId' => $phoneNumber . '@c.us',
                'message' => $message
            ]);
            
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Text send error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send message (alias untuk sendTextMessage - untuk welcome messages)
     */
    public function sendMessage($phoneNumber, $message)
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            
            Log::info('📤 Sending WhatsApp text message', [
                'phone' => $formattedPhone,
                'message_length' => strlen($message)
            ]);

            $result = $this->sendTextMessage($formattedPhone, $message);

            if ($result) {
                Log::info('✅ WhatsApp message sent successfully', [
                    'phone' => $formattedPhone
                ]);
                return true;
            }

            Log::warning('⚠️ WhatsApp API returned non-success', [
                'phone' => $formattedPhone
            ]);
            
            return false;

        } catch (\Exception $e) {
            Log::error('❌ WhatsApp message send failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    protected function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        return $phone;
    }
}