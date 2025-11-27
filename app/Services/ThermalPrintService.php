<?php

namespace App\Services;

use App\Models\Sale;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ThermalPrintService
{
    protected $serverUrl;
    protected $defaultPort;

    public function __construct()
    {
        $this->serverUrl = env('THERMAL_PRINT_SERVER_URL', 'http://localhost:3001');
        $this->defaultPort = env('THERMAL_PRINTER_PORT', 'COM3'); // Sesuaikan dengan port Bluetooth lu
    }

    /**
     * ✅ Get available printer ports
     */
    public function getAvailablePorts()
    {
        try {
            $response = Http::timeout(10)->get("{$this->serverUrl}/api/printer/ports");

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to get ports: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to get printer ports: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * ✅ Print receipt untuk Sale
     */
    public function printReceipt(Sale $sale, ?string $port = null)
    {
        try {
            $sale->load(['customer', 'user', 'saleDetails.product', 'saleDetails.pricePromotion', 'saleDetails.quantityPromotion', 'payments']);

            $payment = $sale->payments->first();
            $receiptData = $this->formatReceiptData($sale, $payment);

            $response = Http::timeout(30)->post("{$this->serverUrl}/api/printer/print", [
                'port' => $port ?? $this->defaultPort,
                'receipt' => $receiptData
            ]);

            if ($response->successful()) {
                Log::info('✅ Receipt printed successfully', [
                    'sale_id' => $sale->id,
                    'transaction_number' => $sale->transaction_number
                ]);

                return [
                    'success' => true,
                    'message' => 'Receipt printed successfully'
                ];
            }

            throw new \Exception('Print failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Failed to print receipt', [
                'error' => $e->getMessage(),
                'sale_id' => $sale->id
            ]);

            return [
                'success' => false,
                'message' => 'Print failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * ✅ Test printer connection
     */
    public function testPrint(?string $port = null)
    {
        try {
            $response = Http::timeout(10)->post("{$this->serverUrl}/api/printer/test", [
                'port' => $port ?? $this->defaultPort
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Test print failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Test print failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * ✅ Format receipt data untuk thermal printer
     */
    protected function formatReceiptData(Sale $sale, $payment = null)
    {
        $items = $sale->saleDetails->map(function ($detail) {
            $item = [
                'product_name' => $detail->product_name,
                'quantity' => $detail->quantity,
                'unit_price' => $detail->unit_price,
                'subtotal' => $detail->subtotal,
                'price_discount_amount' => $detail->price_discount_amount ?? 0,
                'quantity_discount_amount' => $detail->quantity_discount_amount ?? 0,
            ];

            // Add promotion badges
            if ($detail->price_promotion_id && $detail->pricePromotion) {
                $item['price_promotion'] = $detail->pricePromotion->badge_text ?? 'Discount';
            }

            if ($detail->quantity_promotion_id && $detail->quantityPromotion) {
                $item['quantity_promotion'] = $detail->quantityPromotion->badge_text ?? "Buy {$detail->quantity} Get {$detail->free_quantity} FREE";
            }

            return $item;
        })->toArray();

        $amountPaid = $payment ? $payment->amount : $sale->total_price;
        $change = $amountPaid - $sale->total_price;

        return [
            'store_name' => 'SKYRA MART',
            'store_address' => 'Jl. Masjid Daruttaqwa No. 123, Depok',
            'store_phone' => 'Phone: 0889-2114-416',
            
            'transaction_number' => $sale->transaction_number,
            'date' => $sale->sale_date->format('d M Y, H:i'),
            'cashier_name' => $sale->user->name ?? 'Admin',
            'customer_name' => $sale->customer->customer_name ?? 'Walk-in Customer',
            
            'items' => $items,
            
            'subtotal' => $sale->subtotal,
            'discount' => $sale->discount,
            'discount_percentage' => $sale->discount_percentage,
            'tax' => $sale->tax,
            'total_price' => $sale->total_price,
            
            'payment_method' => $payment ? $payment->payment_method : 'cash',
            'payment_channel' => $payment ? $payment->payment_channel : null,
            'amount_paid' => $amountPaid,
            'change' => $change,
            'reference_number' => $payment ? $payment->reference_number : null,
        ];
    }
}