<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'sale_date',
        'customer_id',
        'user_id',
        'subtotal',
        'discount',
        'discount_percentage',
        'tax',
        'total_price',
        'status',
        'notes'
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

   public function processStockReduction()
{
    DB::beginTransaction();
    try {
        if ($this->status !== 'completed') {
            throw new \Exception('Sale must be completed before reducing stock');
        }

        $payment = $this->payments()->where('status', 'completed')->first();
        if ($payment && $payment->stock_reduced) {
            Log::warning('Stock already reduced for sale: ' . $this->transaction_number);
            return true;
        }

        foreach ($this->saleDetails as $detail) {
            $product = Product::lockForUpdate()->find($detail->product_id);
            
            if (!$product) {
                throw new \Exception("Product not found: {$detail->product_name}");
            }

            $purchasedQty = $detail->quantity;
            $freeQty = $detail->free_quantity ?? 0;
            $totalStockToReduce = $purchasedQty + $freeQty;

            // ✅ LOGIKA FEFO: Ambil stock dari batch dengan expiry terdekat
            if ($product->has_expiry) {
                $usedBatches = $product->reduceStockFEFO($totalStockToReduce);
                
                // Create stock history untuk setiap batch yang digunakan
                foreach ($usedBatches as $batchInfo) {
                    $promotionInfo = [];
                    
                    if ($detail->price_promotion_id) {
                        $pricePromo = $detail->pricePromotion;
                        $promotionInfo[] = "Price: {$pricePromo->badge_text}";
                    }
                    
                    if ($detail->quantity_promotion_id) {
                        $qtyPromo = $detail->quantityPromotion;
                        $promotionInfo[] = "Qty: {$qtyPromo->badge_text} (+{$freeQty} FREE)";
                    }
                    
                    $promotionLabel = !empty($promotionInfo) ? " | " . implode(" + ", $promotionInfo) : "";
                    
                    StockHistory::create([
                        'product_id' => $product->id,
                        'batch_id' => $batchInfo['batch_id'],
                        'expiry_date' => $batchInfo['expiry_date'],
                        'type' => 'out',
                        'quantity' => $batchInfo['quantity'],
                        'stock_before' => $batchInfo['remaining_after'] + $batchInfo['quantity'],
                        'stock_after' => $batchInfo['remaining_after'],
                        'reference_type' => 'sales',
                        'reference_id' => $this->id,
                        'description' => "Sale: {$this->transaction_number} | Batch Exp: " . 
                                        date('d M Y', strtotime($batchInfo['expiry_date'])) . 
                                        " | Taken: {$batchInfo['quantity']}" . $promotionLabel,
                        'user_id' => $this->user_id
                    ]);
                }
            } else {
                // ✅ PRODUK TANPA EXPIRY: Langsung kurangi stock normal
                if ($product->stock < $totalStockToReduce) {
                    throw new \Exception("Insufficient stock for {$product->product_name}");
                }

                $oldStock = $product->stock;
                $product->decrement('stock', $totalStockToReduce);

                $promotionInfo = [];
                if ($detail->price_promotion_id) {
                    $promotionInfo[] = "Price: " . $detail->pricePromotion->badge_text;
                }
                if ($detail->quantity_promotion_id) {
                    $promotionInfo[] = "Qty: " . $detail->quantityPromotion->badge_text;
                }
                $promotionLabel = !empty($promotionInfo) ? " | " . implode(" + ", $promotionInfo) : "";

                StockHistory::create([
                    'product_id' => $product->id,
                    'batch_id' => null,
                    'expiry_date' => null,
                    'type' => 'out',
                    'quantity' => $totalStockToReduce,
                    'stock_before' => $oldStock,
                    'stock_after' => $product->fresh()->stock,
                    'reference_type' => 'sales',
                    'reference_id' => $this->id,
                    'description' => "Sale: {$this->transaction_number} | {$purchasedQty} purchased" .
                                    ($freeQty > 0 ? " + {$freeQty} free" : "") . $promotionLabel,
                    'user_id' => $this->user_id
                ]);
            }
        }

        if ($payment) {
            $payment->update(['stock_reduced' => true]);
        }

        DB::commit();
        Log::info('Stock reduced successfully (FEFO) for sale: ' . $this->transaction_number);
        return true;

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Failed to reduce stock: ' . $e->getMessage());
        throw $e;
    }
}

    /**
     * Process loyalty points deduction (called when payment completed)
     */
    public function processLoyaltyDeduction($loyaltyPointsUsed)
    {
        if (!$this->customer_id || $loyaltyPointsUsed <= 0) {
            return true;
        }

        $payment = $this->payments()->where('status', 'completed')->first();
        if ($payment && $payment->loyalty_deducted) {
            Log::warning('Loyalty already deducted for sale: ' . $this->transaction_number);
            return true;
        }

        $customer = Customer::find($this->customer_id);
        if (!$customer) {
            throw new \Exception('Customer not found');
        }

        if (!$customer->useLoyaltyPoints($loyaltyPointsUsed)) {
            throw new \Exception('Failed to deduct loyalty points');
        }

        if ($payment) {
            $payment->update(['loyalty_deducted' => true]);
        }

        Log::info("Loyalty points deducted: {$loyaltyPointsUsed} for sale: {$this->transaction_number}");
        return true;
    }

/**
 * Complete sale (called after payment confirmed)
 * ✅ UPDATED: Added Email sending
 */
public function completeSale($loyaltyPointsUsed = 0)
{
    DB::beginTransaction();
    try {
        // 1. Update status to completed
        $this->update(['status' => 'completed']);

        // 2. Reduce stock
        $this->processStockReduction();

        // 3. Deduct loyalty points if used
        if ($loyaltyPointsUsed > 0) {
            $this->processLoyaltyDeduction($loyaltyPointsUsed);
        }

        // 4. Add purchase to customer (untuk loyalty points baru)
        if ($this->customer_id) {
            $customer = Customer::find($this->customer_id);
            $customer->addPurchase($this->total_price);
        }

        DB::commit();
        Log::info('Sale completed successfully: ' . $this->transaction_number);
        
        // ✅ SEND WHATSAPP (existing)
        $this->sendWhatsAppReceiptFromModel();
        
        // ✅ SEND EMAIL (NEW)
        $this->sendEmailReceipt();
        
        return true;

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Failed to complete sale ' . $this->transaction_number . ': ' . $e->getMessage());
        throw $e;
    }
}

// Add this method to your Sale model (replace existing sendWhatsAppReceiptFromModel)

/**
 * ✅ Send WhatsApp receipt from model (UPDATED dengan Template)
 */
protected function sendWhatsAppReceiptFromModel()
{
    try {
        // Check if customer has phone number
        if (!$this->customer || !$this->customer->phone_number) {
            Log::info('⏭️ Skipping WhatsApp receipt - No customer phone number', [
                'sale_id' => $this->id
            ]);
            return;
        }

        Log::info('🚀 Starting WhatsApp receipt from model', [
            'sale_id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'customer' => $this->customer->customer_name,
            'phone' => $this->customer->phone_number
        ]);

        // Generate PDF
        $pdfService = new \App\Services\ReceiptPdfService();
        $pdfData = $pdfService->generate($this);

        if (!$pdfData) {
            throw new \Exception('Failed to generate PDF receipt');
        }

        // ✅ Send via WhatsApp dengan Template
        $whatsappService = new \App\Services\WhatsAppService();
        $result = $whatsappService->sendReceipt(
            $this->customer->phone_number,
            $this, // Pass Sale object directly
            $pdfData['path'],
            $pdfData['filename']
        );

        if ($result) {
            Log::info('✅ WhatsApp receipt sent successfully from model', [
                'sale_id' => $this->id,
                'customer' => $this->customer->customer_name,
                'phone' => $this->customer->phone_number
            ]);
        }

    } catch (\Exception $e) {
        Log::error('❌ Failed to send WhatsApp receipt from model', [
            'sale_id' => $this->id,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * ✅ Build receipt message from model
 */
protected function buildReceiptMessageFromModel()
{
    $customerName = $this->customer->customer_name;
    $totalItems = $this->saleDetails->sum('quantity');
    $payment = $this->payments->first();
    
    $message = "🛒 *SkyraMart - Digital Receipt*\n\n";
    $message .= "Dear *{$customerName}*,\n";
    $message .= "Thank you for shopping with us! 💚\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "📋 *Transaction Details*\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "🧾 Receipt No: *{$this->transaction_number}*\n";
    $message .= "📅 Date: {$this->sale_date->format('d M Y, H:i')}\n";
    $message .= "👤 Cashier: {$this->user->name}\n\n";
    
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "🛍️ *Order Summary*\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Items
    foreach ($this->saleDetails as $index => $detail) {
        $itemNum = $index + 1;
        $message .= "{$itemNum}. {$detail->product_name}\n";
        $message .= "   {$detail->quantity} x Rp" . number_format($detail->unit_price, 0, ',', '.') . " = Rp" . number_format($detail->subtotal, 0, ',', '.') . "\n";
        
        if ($detail->price_promotion_id || $detail->quantity_promotion_id) {
            if ($detail->price_promotion_id) {
                $message .= "   💰 " . ($detail->pricePromotion->badge_text ?? 'Discount') . ": -Rp" . number_format($detail->price_discount_amount, 0, ',', '.') . "\n";
            }
            if ($detail->quantity_promotion_id && $detail->free_quantity > 0) {
                $message .= "   🎁 Bonus: +{$detail->free_quantity} FREE\n";
            }
        }
        $message .= "\n";
    }
    
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "💰 *Payment Details*\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "Subtotal: Rp" . number_format($this->subtotal, 0, ',', '.') . "\n";
    
    if ($this->discount > 0) {
        $message .= "Discount ({$this->discount_percentage}%): -Rp" . number_format($this->discount, 0, ',', '.') . "\n";
    }
    
    if ($this->tax > 0) {
        $message .= "Tax: Rp" . number_format($this->tax, 0, ',', '.') . "\n";
    }
    
    $message .= "*TOTAL: Rp" . number_format($this->total_price, 0, ',', '.') . "*\n\n";
    
    if ($payment) {
        $paymentMethod = strtoupper($payment->payment_method);
        $message .= "Payment Method: *{$paymentMethod}*\n";
        
        if ($payment->payment_channel) {
            $message .= "Channel: {$payment->payment_channel}\n";
        }
        
        if ($payment->payment_method === 'cash') {
            $totalPaid = $payment->amount;
            $change = $totalPaid - $this->total_price;
            $message .= "Cash Paid: Rp" . number_format($totalPaid, 0, ',', '.') . "\n";
            $message .= "Change: Rp" . number_format($change, 0, ',', '.') . "\n";
        }
    }
    
    $message .= "\n━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "📄 *Your receipt will be sent as PDF*\n";
    $message .= "_If PDF fails, you'll receive a download link_\n\n";
    $message .= "Need help? Contact us:\n";
    $message .= "📞 WA: 0889-2114-416\n";
    $message .= "📍 Jl. Masjid Daruttaqwa No. 123, Depok\n\n";
    $message .= "Thank you for your trust! 🙏\n";
    $message .= "_Please save this receipt for your records_\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "💚 *SkyraMart* - Your Trusted Store";
    
    return $message;
}

    /**
     * Cancel sale and restore stock (if already reduced)
     */
    public function cancelSale()
    {
        DB::beginTransaction();
        try {
            // If completed, restore stock
            if ($this->status === 'completed') {
                foreach ($this->saleDetails as $detail) {
                    $product = Product::lockForUpdate()->find($detail->product_id);
                    if ($product) {
                        $oldStock = $product->stock;
                        $product->increment('stock', $detail->quantity);

                        StockHistory::create([
                            'product_id' => $product->id,
                            'type' => 'in',
                            'quantity' => $detail->quantity,
                            'stock_before' => $oldStock,
                            'stock_after' => $product->fresh()->stock,
                            'reference_type' => 'sales_return',
                            'reference_id' => $this->id,
                            'description' => "Sale cancelled: {$this->transaction_number}",
                            'user_id' => auth()->id() ?? $this->user_id
                        ]);
                    }
                }

                // Restore customer purchase
                if ($this->customer_id) {
                    $customer = Customer::find($this->customer_id);
                    $customer->decrement('total_purchase', $this->total_price);
                    $customer->syncLoyaltyPoints();
                }
            }

            // Update status
            $this->update(['status' => 'cancelled']);
            $this->payments()->update(['status' => 'failed']);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    // Helper method to calculate auto discount
    public static function calculateAutoDiscount($subtotal)
    {
        // 1.5% per 100,000
        $discountPercentage = floor($subtotal / 100000) * 1.5;
        $discountAmount = ($subtotal * $discountPercentage) / 100;
        
        return [
            'percentage' => $discountPercentage,
            'amount' => $discountAmount
        ];
    }

    /**
 * ✅ FIXED: Send Email Receipt with proper PDF handling
 */
protected function sendEmailReceipt()
{
    try {
        // Check if customer has email
        if (!$this->customer || !$this->customer->email) {
            Log::info('⏭️ Skipping Email receipt - No customer email', [
                'sale_id' => $this->id,
                'transaction_number' => $this->transaction_number
            ]);
            return;
        }

        Log::info('📧 Starting Email receipt process', [
            'sale_id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'customer' => $this->customer->customer_name,
            'email' => $this->customer->email
        ]);

        // ✅ Load relationships FIRST
        $this->load(['customer', 'user', 'saleDetails.product', 'saleDetails.pricePromotion', 'saleDetails.quantityPromotion', 'payments']);

        // Generate PDF
        $pdfService = new \App\Services\ReceiptPdfService();
        $pdfData = $pdfService->generate($this);

        if (!$pdfData || !isset($pdfData['path']) || !file_exists($pdfData['path'])) {
            throw new \Exception('Failed to generate PDF receipt or PDF file not found');
        }

        Log::info('✅ PDF generated for email', [
            'sale_id' => $this->id,
            'path' => $pdfData['path'],
            'exists' => file_exists($pdfData['path']),
            'size_kb' => round(filesize($pdfData['path']) / 1024, 2)
        ]);

        // ✅ Send email SYNC (not queued) to ensure PDF is sent before deletion
        \Illuminate\Support\Facades\Mail::to($this->customer->email)
            ->send(new \App\Mail\SaleReceiptMail($this, $pdfData['path']));

        Log::info('✅ Email receipt sent successfully', [
            'sale_id' => $this->id,
            'customer' => $this->customer->customer_name,
            'email' => $this->customer->email,
            'pdf_attached' => true
        ]);

        // ✅ Cleanup PDF after sending (with delay to ensure sent)
        sleep(2); // Wait 2 seconds to ensure email is sent
        
        if (file_exists($pdfData['path'])) {
            unlink($pdfData['path']);
            Log::info('🗑️ Cleaned up email PDF file', ['path' => $pdfData['path']]);
        }

    } catch (\Exception $e) {
        Log::error('❌ Failed to send Email receipt', [
            'sale_id' => $this->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // Cleanup PDF on error
        if (isset($pdfData['path']) && file_exists($pdfData['path'])) {
            unlink($pdfData['path']);
        }
        
        // Don't throw - transaction should still succeed even if email fails
    }
}
}