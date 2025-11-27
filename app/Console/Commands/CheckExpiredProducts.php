<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockHistory;
use App\Models\LossReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckExpiredProducts extends Command
{
    protected $signature = 'products:check-expired';
    protected $description = 'Check expired batches, record losses, and adjust stock to zero';

    public function handle()
    {
        $this->info('Checking for expired product batches...');

        DB::beginTransaction();
        try {
            $processedCount = 0;

            // ✅ Handle expired batches with remaining stock
            $expiredBatches = ProductBatch::where('quantity_remaining', '>', 0)
                ->where('expiry_date', '<', now())
                ->with('product')
                ->get();

            foreach ($expiredBatches as $batch) {
                $product = $batch->product;
                $expiredQuantity = $batch->quantity_remaining;

                // ✅ BARU: Record loss report SEBELUM menghapus stock
                LossReport::create([
                    'product_id' => $product->id,
                    'batch_id' => $batch->id,
                    'batch_number' => $batch->batch_number,
                    'expiry_date' => $batch->expiry_date,
                    'quantity_expired' => $expiredQuantity,
                    'purchase_price' => $product->purchase_price,
                    'total_loss' => $expiredQuantity * $product->purchase_price,
                    'notes' => "Auto-recorded: Batch expired on {$batch->expiry_date->format('Y-m-d')}",
                    'recorded_by' => 1 // System user
                ]);

                // Create stock history for batch expiry
                StockHistory::create([
                    'product_id' => $product->id,
                    'type' => 'adjustment',
                    'quantity' => $expiredQuantity,
                    'stock_before' => $product->stock,
                    'stock_after' => max(0, $product->stock - $expiredQuantity),
                    'reference_type' => 'batch_expired',
                    'reference_id' => $batch->id,
                    'description' => "Batch #{$batch->id} (Batch: {$batch->batch_number}) expired on {$batch->expiry_date->format('Y-m-d')}. Stock removed and loss recorded.",
                    'user_id' => 1
                ]);

                // Zero out batch quantity
                $batch->update(['quantity_remaining' => 0]);

                // Decrement product stock
                $product->decrement('stock', $expiredQuantity);

                $this->line("✓ Batch #{$batch->id} ({$product->product_name}) - Loss recorded: Rp" . number_format($expiredQuantity * $product->purchase_price, 0, ',', '.'));
                $processedCount++;

                Log::info("Expired batch loss recorded", [
                    'batch_id' => $batch->id,
                    'product_name' => $product->product_name,
                    'quantity' => $expiredQuantity,
                    'loss_amount' => $expiredQuantity * $product->purchase_price
                ]);
            }

            // Sync product stock with available batches
            $productsWithBatches = Product::whereHas('batches')->get();
            
            foreach ($productsWithBatches as $product) {
                $totalAvailableStock = $product->batches()
                    ->where('quantity_remaining', '>', 0)
                    ->where('expiry_date', '>=', now())
                    ->sum('quantity_remaining');

                if ($product->stock != $totalAvailableStock) {
                    $product->update(['stock' => $totalAvailableStock]);
                    $this->line("✓ {$product->product_name} - Stock synchronized to {$totalAvailableStock}");
                }
            }

            DB::commit();

            if ($processedCount > 0) {
                $this->info("\n✓ Successfully processed {$processedCount} expired batch(es) and recorded losses.");
            } else {
                $this->info('No expired batches found.');
            }
            
            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            Log::error("Expired batches check failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }
}