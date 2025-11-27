<?php

namespace App\Services;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ReceiptPdfService
{
    public function generate(Sale $sale)
    {
        try {
            $sale->load(['customer', 'user', 'saleDetails.product', 'payments']);

            $customerName = $sale->customer 
                ? Str::slug($sale->customer->customer_name) 
                : 'WalkIn';
            
            $fileName = sprintf(
                'SkyraMart_Receipt_%s_%s_%s.pdf',
                $customerName,
                $sale->transaction_number,
                $sale->sale_date->format('dMY')
            );

            $receiptDir = storage_path('app/receipts');
            if (!is_dir($receiptDir)) {
                mkdir($receiptDir, 0755, true);
            }

            $fullPath = $receiptDir . DIRECTORY_SEPARATOR . $fileName;

            // ✅ Use ultra compact template
            $pdf = Pdf::loadView('receipts.pdf-ultra', ['sale' => $sale])
                ->setPaper([0, 0, 198, 400], 'portrait') // Very small
                ->setOption('enable-local-file-access', true)
                ->setOption('enable-javascript', false)
                ->setOption('no-images', true)
                ->setOption('dpi', 72)
                ->setOption('image-dpi', 72);

            $output = $pdf->output();
            
            // ✅ Try to compress (if possible)
            file_put_contents($fullPath, $output);

            if (!file_exists($fullPath)) {
                throw new \Exception('PDF not created');
            }

            $fileSize = filesize($fullPath);
            
            Log::info('✅ PDF generated', [
                'path' => $fullPath,
                'size_kb' => round($fileSize / 1024, 2),
                'filename' => $fileName
            ]);

            return [
                'path' => $fullPath,
                'filename' => $fileName,
                'size' => $fileSize
            ];

        } catch (\Exception $e) {
            Log::error('❌ PDF Generation Error', [
                'error' => $e->getMessage(),
                'sale_id' => $sale->id ?? 'unknown'
            ]);
            return null;
        }
    }
}