<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DisableExpiredProductPromotions extends Command
{
    protected $signature = 'promotions:disable-expired-products';
    protected $description = 'Disable promotions when products have no available batches or all batches expired';

    public function handle()
    {
        $this->info('Starting to check expired products with active promotions...');

        try {
            $affectedPromotions = 0;

            // ✅ CRITICAL: Check ALL active promotions
            $activePromotions = Promotion::where('is_active', true)->get();

            foreach ($activePromotions as $promotion) {
                $validProducts = 0;
                $productIds = [];

                // Get product IDs from target_ids or pivot table
                if ($promotion->target_type === 'specific_products' && $promotion->target_ids) {
                    $productIds = is_array($promotion->target_ids) 
                        ? $promotion->target_ids 
                        : json_decode($promotion->target_ids, true);
                } else {
                    $productIds = DB::table('promotion_products')
                        ->where('promotion_id', $promotion->id)
                        ->pluck('product_id')
                        ->toArray();
                }

                if (empty($productIds)) {
                    continue;
                }

                // ✅ CRITICAL: Check if products have AVAILABLE batches
                foreach ($productIds as $productId) {
                    $product = Product::find($productId);
                    
                    if (!$product || !$product->is_active || $product->stock <= 0) {
                        continue; // Skip inactive or out-of-stock products
                    }

                    // ✅ KEY FIX: Check if product has AVAILABLE (non-empty) batches
                    if ($product->has_expiry) {
                        $hasAvailableBatches = $product->batches()
                            ->available() // quantity_remaining > 0
                            ->notExpired() // expiry_date >= now
                            ->exists();
                        
                        if ($hasAvailableBatches) {
                            $validProducts++;
                        }
                    } else {
                        // Products without expiry are always valid if active and in stock
                        $validProducts++;
                    }
                }

                // ✅ Deactivate promotion if NO valid products OR promotion expired
                $shouldDeactivate = false;
                $reason = '';

                if ($validProducts === 0) {
                    $shouldDeactivate = true;
                    $reason = 'No products with available stock/batches';
                } elseif ($promotion->end_date && $promotion->end_date < now()) {
                    $shouldDeactivate = true;
                    $reason = 'Promotion end date passed';
                }

                if ($shouldDeactivate) {
                    $promotion->update([
                        'is_active' => false,
                        'description' => ($promotion->description ?? '') . " [AUTO-DISABLED: {$reason}]"
                    ]);
                    
                    $affectedPromotions++;
                    $this->warn("Disabled: {$promotion->name} (ID: {$promotion->id}) - {$reason}");
                    
                    Log::info("Promotion auto-disabled", [
                        'promotion_id' => $promotion->id,
                        'promotion_name' => $promotion->name,
                        'reason' => $reason,
                        'valid_products' => $validProducts
                    ]);
                }
            }

            $this->info("Process completed. Disabled {$affectedPromotions} promotions.");
            
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error occurred: ' . $e->getMessage());
            Log::error('Error in DisableExpiredProductPromotions command', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
    }
}