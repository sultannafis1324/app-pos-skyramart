<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateExpiryPromotions extends Command
{
    protected $signature = 'promotions:create-expiry-promotions';
    protected $description = 'Auto-create promotions for products near expiry date (checks AVAILABLE batches only)';

    public function handle()
    {
        $this->info('Starting expiry promotions creation...');
        
        try {
            DB::beginTransaction();
            
            $allExpiryProducts = []; // 0-30 days
            $h7Products = [];        // 0-7 days

            // ✅ FIXED: Only check products that have AVAILABLE batches with stock
            $productsWithExpiry = Product::where('has_expiry', true)
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->whereHas('batches', function($query) {
                    $query->available()->notExpired(); // Must have available stock
                })
                ->get();

            foreach ($productsWithExpiry as $product) {
                // ✅ CRITICAL: Get nearest AVAILABLE batch only (with stock > 0)
                $nearestAvailableBatch = $product->batches()
                    ->available() // quantity_remaining > 0
                    ->notExpired() // expiry_date >= now
                    ->FEFO() // order by expiry_date asc
                    ->first();
                
                if (!$nearestAvailableBatch || !$nearestAvailableBatch->expiry_date) {
                    continue; // Skip if no available batch with stock
                }
                
                $daysUntilExpiry = now()->diffInDays($nearestAvailableBatch->expiry_date, false);
                
                // All products with 0-30 days get 50% discount
                if ($daysUntilExpiry <= 30) {
                    $allExpiryProducts[] = $product->id;
                }
                
                // Products with 0-7 days get additional Buy 1 Get 2
                if ($daysUntilExpiry <= 7) {
                    $h7Products[] = $product->id;
                }
            }

            // 1. Create/Update 50% OFF for ALL products 0-30 days
            if (!empty($allExpiryProducts)) {
                $this->createOrUpdateGroupPromotion(
                    'H30',
                    'percentage',
                    $allExpiryProducts,
                    50,
                    null,
                    null
                );
            } else {
                // Deactivate H30 promotion if no products
                $this->deactivatePromotionByCode('EXPIRY-H30-PCT');
            }

            // 2. Create/Update Buy 1 Get 2 for H-7 products only
            if (!empty($h7Products)) {
                $this->createOrUpdateGroupPromotion(
                    'H7',
                    'buy_x_get_y',
                    $h7Products,
                    null,
                    1,
                    2
                );
            } else {
                // Deactivate H7 promotion if no products
                $this->deactivatePromotionByCode('EXPIRY-H7-BXG');
            }

            // Deactivate expired promotions
            $deactivated = $this->deactivateExpiredPromotions();

            DB::commit();

            $this->info("✓ Products with 50% OFF (0-30 days): " . count($allExpiryProducts));
            $this->info("✓ Products with Buy 1 Get 2 (0-7 days): " . count($h7Products));
            $this->info("✓ Deactivated: {$deactivated} expired promotions");
            
            Log::info('Expiry promotions updated', [
                'all_expiry_products' => count($allExpiryProducts),
                'h7_products' => count($h7Products),
                'deactivated' => $deactivated
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error: ' . $e->getMessage());
            Log::error('Expiry promotions creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function createOrUpdateGroupPromotion(
        string $period,
        string $type,
        array $productIds,
        $discountValue = null,
        $buyQty = null,
        $getQty = null
    ) {
        $typeSuffix = $type === 'percentage' ? 'PCT' : 'BXG';
        $code = "EXPIRY-{$period}-{$typeSuffix}";

        $existingPromo = Promotion::where('code', $code)->first();

        // ✅ CRITICAL: Calculate latest expiry date from AVAILABLE batches only
        $latestExpiryDate = null;
        
        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $nearestBatch = $product->batches()
                    ->available() // Must have stock
                    ->notExpired()
                    ->FEFO()
                    ->first();
                    
                if ($nearestBatch && $nearestBatch->expiry_date && (!$latestExpiryDate || $nearestBatch->expiry_date > $latestExpiryDate)) {
                    $latestExpiryDate = $nearestBatch->expiry_date;
                }
            }
        }

        $promoData = [
            'name' => $this->generateGroupPromotionName($period, $type),
            'code' => $code,
            'description' => $this->generateGroupDescription($period, $type),
            'type' => $type,
            'start_date' => now(),
            'end_date' => $latestExpiryDate,
            'target_type' => 'specific_products',
            'target_ids' => $productIds,
            'is_active' => true,
            'priority' => $period === 'H7' ? 95 : 85,
            'is_stackable' => true,
        ];

        if ($type === 'percentage') {
            $promoData['discount_value'] = $discountValue;
            $promoData['badge_text'] = "HEMAT {$discountValue}% - EXPIRY SOON!";
            $promoData['badge_color'] = $period === 'H7' ? '#FF0000' : '#FF6B6B';
        } elseif ($type === 'buy_x_get_y') {
            $promoData['buy_quantity'] = $buyQty;
            $promoData['get_quantity'] = $getQty;
            $promoData['badge_text'] = "BELI {$buyQty} GRATIS {$getQty} - EXPIRY ALERT!";
            $promoData['badge_color'] = '#FF0000';
        }

        if ($existingPromo) {
            $existingPromo->update($promoData);
            $existingPromo->products()->sync($productIds);
            
            $this->line("  → Updated: {$promoData['name']} ({$promoData['code']})");
            $this->line("     Products: " . count($productIds) . " items");
        } else {
            $promotion = Promotion::create($promoData);
            $promotion->products()->sync($productIds);
            
            $this->line("  ✓ Created: {$promoData['name']} ({$promoData['code']})");
            $this->line("     Products: " . count($productIds) . " items");
        }
    }

    private function generateGroupPromotionName(string $period, string $type): string
    {
        if ($type === 'percentage') {
            return "Expiry Promo {$period} - 50% OFF";
        } else {
            return "Expiry Special {$period} - Beli 1 Gratis 2";
        }
    }

    private function generateGroupDescription(string $period, string $type): string
    {
        $typeDesc = $type === 'percentage' ? '50% discount' : 'Buy 1 Get 2 Free';
        return "Auto-generated expiry promotion for products near expiry date. {$typeDesc} for products with {$period} or less until expiry.";
    }

    // ✅ NEW: Helper to deactivate promotion by code
    private function deactivatePromotionByCode(string $code): void
    {
        $promotion = Promotion::where('code', $code)
            ->where('is_active', true)
            ->first();
            
        if ($promotion) {
            $promotion->update(['is_active' => false]);
            $this->line("  ✓ Deactivated: {$promotion->name} (no eligible products)");
        }
    }

    private function deactivateExpiredPromotions(): int
    {
        $deactivated = 0;
        
        $promotions = Promotion::where('code', 'like', 'EXPIRY-%')
            ->where('is_active', true)
            ->get();

        foreach ($promotions as $promotion) {
            // ✅ CRITICAL: Check if any target products have AVAILABLE batches near expiry
            $validProducts = 0;
            
            foreach ($promotion->target_ids ?? [] as $productId) {
                $product = Product::find($productId);
                if ($product && $product->stock > 0 && $product->is_active) {
                    // Check if product has AVAILABLE batches that are not expired
                    $hasAvailableBatches = $product->batches()
                        ->available() // quantity_remaining > 0
                        ->notExpired() // expiry_date >= now
                        ->exists();
                    
                    if ($hasAvailableBatches) {
                        $validProducts++;
                    }
                }
            }

            // Deactivate if no valid products OR promotion end date passed
            if ($validProducts === 0 || ($promotion->end_date && $promotion->end_date < now())) {
                $promotion->update(['is_active' => false]);
                $deactivated++;
                $this->line("  ✓ Deactivated: {$promotion->name} (valid products: {$validProducts})");
            }
        }

        return $deactivated;
    }
}