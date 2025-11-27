<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'discount_value',
        'max_discount',
        'min_purchase',
        'buy_quantity',
        'get_quantity',
        'bundle_price',
        'bundle_quantity',
        'cashback_percentage',
        'max_cashback',
        'start_date',
        'end_date',
        'usage_limit',
        'usage_per_customer',
        'current_usage',
        'target_type',
        'target_ids',
        'is_active',
        'priority',
        'is_stackable',
        'badge_text',
        'badge_color',
        'image'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'bundle_price' => 'decimal:2',
        'cashback_percentage' => 'decimal:2',
        'max_cashback' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'target_ids' => 'array',
        'is_active' => 'boolean',
        'is_stackable' => 'boolean',
        'current_usage' => 'integer',
    ];

    // Relationships
    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_products');
    }

    public function usages()
    {
        return $this->hasMany(PromotionUsage::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->where(function($q) {
                $q->whereNull('usage_limit')
                  ->orWhereColumn('current_usage', '<', 'usage_limit');
            });
    }

    /**
     * ✅ UPDATED: Scope untuk mendapatkan promo yang berlaku untuk product tertentu
     * Exclude products yang sudah expired
     */
    public function scopeForProduct($query, $productId)
    {
        // Ambil data product beserta category_id
        $product = \App\Models\Product::find($productId);
        
        if (!$product) {
            return $query->whereRaw('1 = 0'); // Return empty
        }

        // ✅ NEW: Jika produk expired, return empty
        if ($product->is_expired) {
            return $query->whereRaw('1 = 0');
        }

        return $query->active()
            ->where(function($q) use ($productId, $product) {
                // 1. Target semua produk
                $q->where('target_type', 'all')
                
                // 2. Target produk spesifik (dari target_ids JSON)
                ->orWhere(function($subQ) use ($productId) {
                    $subQ->where('target_type', 'specific_products')
                         ->where(function($jsonQ) use ($productId) {
                             // Support untuk berbagai format JSON
                             $jsonQ->whereJsonContains('target_ids', $productId)
                                   ->orWhereJsonContains('target_ids', (string)$productId);
                         });
                })
                
                // 3. Target produk spesifik (dari pivot table)
                ->orWhere(function($subQ) use ($productId) {
                    $subQ->where('target_type', 'specific_products')
                         ->whereHas('products', function($pivotQ) use ($productId) {
                             $pivotQ->where('products.id', $productId);
                         });
                })
                
                // 4. Target kategori
                ->orWhere(function($subQ) use ($product) {
                    if ($product->category_id) {
                        $subQ->where('target_type', 'category')
                             ->where(function($jsonQ) use ($product) {
                                 $jsonQ->whereJsonContains('target_ids', $product->category_id)
                                       ->orWhereJsonContains('target_ids', (string)$product->category_id);
                             });
                    }
                });
            });
    }

    // Helper Methods
    public function isActive(): bool
    {
        $now = now();
        
        if (!$this->is_active) {
            return false;
        }
        
        if ($this->start_date > $now) {
            return false;
        }
        
        if ($this->end_date !== null && $this->end_date < $now) {
            return false;
        }
        
        if ($this->usage_limit !== null && $this->current_usage >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function canBeUsedBy($customerId): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->usage_per_customer) {
            $customerUsage = $this->usages()
                ->where('customer_id', $customerId)
                ->count();
            
            return $customerUsage < $this->usage_per_customer;
        }

        return true;
    }

    /**
     * ✅ UPDATED: Check apakah promo berlaku untuk product tertentu
     * Exclude products yang expired
     */
    public function appliesTo($productId): bool
    {
        // Cek apakah promo masih aktif
        if (!$this->isActive()) {
            return false;
        }

        // ✅ NEW: Cek apakah product expired
        $product = \App\Models\Product::find($productId);
        if (!$product || $product->is_expired) {
            return false;
        }

        // Jika target semua produk
        if ($this->target_type === 'all') {
            return true;
        }

        // Jika target produk spesifik
        if ($this->target_type === 'specific_products') {
            // Cek di target_ids
            if ($this->target_ids && in_array($productId, $this->target_ids)) {
                return true;
            }
            
            // Cek di pivot table
            if ($this->products()->where('products.id', $productId)->exists()) {
                return true;
            }
            
            return false;
        }

        // Jika target kategori
        if ($this->target_type === 'category') {
            if ($product && $product->category_id && $this->target_ids) {
                return in_array($product->category_id, $this->target_ids);
            }
            
            return false;
        }

        return false;
    }

    /**
     * Calculate discount for a product
     */
    public function calculateDiscount($price, $quantity = 1)
    {
        if (!$this->isActive()) {
            return 0;
        }

        switch ($this->type) {
            case 'percentage':
                $discount = ($price * $quantity) * ($this->discount_value / 100);
                if ($this->max_discount) {
                    $discount = min($discount, $this->max_discount);
                }
                return $discount;

            case 'fixed':
                return min($this->discount_value, $price * $quantity);

            case 'buy_x_get_y':
                if ($quantity >= $this->buy_quantity) {
                    $freeItems = floor($quantity / $this->buy_quantity) * $this->get_quantity;
                    return $price * $freeItems;
                }
                return 0;

            case 'bundle':
                if ($quantity >= $this->bundle_quantity) {
                    $normalPrice = $price * $quantity;
                    $bundles = floor($quantity / $this->bundle_quantity);
                    $remaining = $quantity % $this->bundle_quantity;
                    $bundleTotal = ($bundles * $this->bundle_price) + ($remaining * $price);
                    return $normalPrice - $bundleTotal;
                }
                return 0;

            case 'cashback':
                $cashback = ($price * $quantity) * ($this->cashback_percentage / 100);
                if ($this->max_cashback) {
                    $cashback = min($cashback, $this->max_cashback);
                }
                return $cashback;

            default:
                return 0;
        }
    }

    public function getFinalPrice($originalPrice, $quantity = 1)
    {
        $discount = $this->calculateDiscount($originalPrice, $quantity);
        $total = ($originalPrice * $quantity) - $discount;
        return max(0, $total);
    }

    public function getBadgeTextAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return match($this->type) {
            'percentage' => "DISKON {$this->discount_value}%",
            'fixed' => "HEMAT Rp " . number_format($this->discount_value, 0, ',', '.'),
            'buy_x_get_y' => "BELI {$this->buy_quantity} GRATIS {$this->get_quantity}",
            'bundle' => "PAKET HEMAT",
            'cashback' => "CASHBACK {$this->cashback_percentage}%",
            'seasonal' => strtoupper($this->name),
            default => "PROMO"
        };
    }

    public function isExpired(): bool
    {
        return $this->end_date !== null && $this->end_date < now();
    }

    public function getRemainingDays(): ?int
    {
        if ($this->end_date === null) {
            return null;
        }
        
        $days = now()->diffInDays($this->end_date, false);
        return $days >= 0 ? $days : 0;
    }

    /**
     * Check if promotion is auto-generated expiry promotion
     */
    public function isExpiryPromotion(): bool
    {
        return str_starts_with($this->code ?? '', 'EXPIRY-');
    }

    /**
     * Scope untuk expiry promotions
     */
    public function scopeExpiryPromotions($query)
    {
        return $query->where('code', 'like', 'EXPIRY-%');
    }
}