<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'product_name',
        'description',
        'category_id',
        'supplier_id',
        'purchase_price',
        'selling_price',
        'stock',
        'minimum_stock',
        'unit',
        'barcode',
        'has_expiry', 
        'image',
        'is_active'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
        'is_active' => 'boolean',
        'has_expiry' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            if ($product->stock > 0) {
                StockHistory::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $product->stock,
                    'stock_before' => 0,
                    'stock_after' => $product->stock,
                    'reference_type' => 'initial_stock',
                    'reference_id' => null,
                    'description' => 'Initial stock when product created',
                    'user_id' => Auth::id() ?? 1
                ]);
            }
        });
    }

    // ✅ FIXED: Get effective expiry date from AVAILABLE batches only
public function getEffectiveExpiryDate()
{
    if (!$this->has_expiry) {
        return null;
    }

    // Check if product has batches with AVAILABLE stock (FEFO)
    $nearestAvailableBatch = $this->batches()
        ->available()
        ->notExpired()
        ->FEFO()
        ->first();

    if ($nearestAvailableBatch) {
        return $nearestAvailableBatch->expiry_date;
    }

    // Fallback to product's own expiry_date if no available batches
    return $this->expiry_date;
}

    // ✅ FIXED: Check if product is expired based on AVAILABLE batches
public function getIsExpiredAttribute()
{
    if (!$this->has_expiry) {
        return false;
    }

    // Check if there are any AVAILABLE (non-empty) batches
    $hasAvailableBatches = $this->batches()
        ->available()
        ->notExpired()
        ->exists();

    // If product has batches, check if there are available non-expired batches
    if ($this->batches()->exists()) {
        return !$hasAvailableBatches;
    }

    // If no batches, check product's own expiry_date
    return $this->expiry_date && $this->expiry_date->isPast();
}

    // ✅ FIXED: Check near expiry from AVAILABLE batches only
public function getIsNearExpiryAttribute()
{
    if (!$this->has_expiry) {
        return false;
    }

    $effectiveExpiryDate = $this->getEffectiveExpiryDate();
    
    if (!$effectiveExpiryDate) {
        return false;
    }
    
    $daysUntilExpiry = now()->diffInDays($effectiveExpiryDate, false);
    return $daysUntilExpiry >= 0 && $daysUntilExpiry <= 30;
}

    // ✅ UPDATED: Get days until expiry
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->has_expiry) {
            return null;
        }

        $effectiveExpiryDate = $this->getEffectiveExpiryDate();
        
        if (!$effectiveExpiryDate) {
            return null;
        }
        
        return now()->diffInDays($effectiveExpiryDate, false);
    }

    // ✅ UPDATED: Scopes untuk expired products
    public function scopeExpired($query)
    {
        return $query->where('has_expiry', true)
            ->where(function($q) {
                // Products with expired batches that have stock
                $q->whereHas('batches', function($subQ) {
                    $subQ->where('quantity_remaining', '>', 0)
                         ->where('expiry_date', '<', now());
                })
                // OR products without batches but with expired date
                ->orWhere(function($subQ) {
                    $subQ->whereDoesntHave('batches')
                         ->where('expiry_date', '<', now());
                })
                // OR products with only expired batches (all batches expired)
                ->orWhereDoesntHave('batches', function($subQ) {
                    $subQ->where('quantity_remaining', '>', 0)
                         ->where('expiry_date', '>=', now());
                });
            });
    }

    // ✅ UPDATED: Scope for near expiry products
    public function scopeNearExpiry($query)
    {
        $thirtyDaysFromNow = now()->addDays(30);
        
        return $query->where('has_expiry', true)
            ->where(function($q) use ($thirtyDaysFromNow) {
                // Products with batches near expiry
                $q->whereHas('batches', function($subQ) use ($thirtyDaysFromNow) {
                    $subQ->where('quantity_remaining', '>', 0)
                         ->whereBetween('expiry_date', [now(), $thirtyDaysFromNow]);
                })
                // OR products without batches near expiry
                ->orWhere(function($subQ) use ($thirtyDaysFromNow) {
                    $subQ->whereDoesntHave('batches')
                         ->whereBetween('expiry_date', [now(), $thirtyDaysFromNow]);
                });
            });
    }

    public function scopeWithExpiry($query)
    {
        return $query->where('has_expiry', true);
    }

    // Relationships (unchanged)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }

    // Other methods...
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'minimum_stock');
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock <= $this->minimum_stock;
    }

    public static function generateUniqueCode($productName)
    {
        $words = explode(' ', strtoupper(trim($productName)));
        
        if (count($words) >= 2) {
            $code = implode('', array_map(fn($w) => $w[0], array_slice($words, 0, 3)));
        } else {
            $code = substr($words[0], 0, 3);
        }
        
        do {
            $randomNum = rand(100, 999);
            $productCode = "{$code}-{$randomNum}";
        } while (self::where('product_code', $productCode)->exists());
        
        return $productCode;
    }

    public static function generateBarcode()
    {
        do {
            $barcode = date('ymdHis') . rand(10000, 99999);
        } while (self::where('barcode', $barcode)->exists());
        
        return $barcode;
    }

    // ✅ UPDATED: Get active promotions - exclude expired products
    public function getActivePromotions()
    {
        if ($this->is_expired) {
            return [
                'price_promotion' => null,
                'quantity_promotion' => null,
            ];
        }

        $promotions = Promotion::forProduct($this->id)
            ->orderBy('priority', 'desc')
            ->get();

        return [
            'price_promotion' => $promotions->whereIn('type', ['percentage', 'fixed'])->first(),
            'quantity_promotion' => $promotions->where('type', 'buy_x_get_y')->first(),
        ];
    }

    public function getPriceWithMultiPromotions($quantity = 1)
    {
        $originalPrice = $this->selling_price;
        $finalPricePerUnit = $originalPrice;
        $priceDiscountPerUnit = 0;
        $freeQuantity = 0;
        $quantityDiscountAmount = 0;

        if ($this->is_expired) {
            $subtotalBeforeDiscount = $originalPrice * $quantity;
            
            return [
                'original_price' => $originalPrice,
                'final_price_per_unit' => $finalPricePerUnit,
                'price_discount_per_unit' => 0,
                'total_price_discount' => 0,
                'free_quantity' => 0,
                'quantity_discount_amount' => 0,
                'total_savings' => 0,
                'subtotal' => $subtotalBeforeDiscount,
                'total_quantity' => $quantity,
                'price_promotion' => null,
                'quantity_promotion' => null,
                'has_price_promotion' => false,
                'has_quantity_promotion' => false,
                'has_multiple_promotions' => false,
            ];
        }

        $promotions = $this->getActivePromotions();
        $pricePromotion = $promotions['price_promotion'];
        $quantityPromotion = $promotions['quantity_promotion'];

        if ($pricePromotion) {
            if ($pricePromotion->type === 'percentage') {
                $priceDiscountPerUnit = ($originalPrice * $pricePromotion->discount_value) / 100;
                
                if ($pricePromotion->max_discount && $priceDiscountPerUnit > $pricePromotion->max_discount) {
                    $priceDiscountPerUnit = $pricePromotion->max_discount;
                }
            } elseif ($pricePromotion->type === 'fixed') {
                $priceDiscountPerUnit = min($pricePromotion->discount_value, $originalPrice);
            }
            
            $finalPricePerUnit = $originalPrice - $priceDiscountPerUnit;
        }

        if ($quantityPromotion && $quantityPromotion->type === 'buy_x_get_y' && $quantity >= $quantityPromotion->buy_quantity) {
            $buyQty = $quantityPromotion->buy_quantity;
            $getQty = $quantityPromotion->get_quantity;
            
            $freeQuantity = floor($quantity / $buyQty) * $getQty;
            $quantityDiscountAmount = $freeQuantity * $finalPricePerUnit;
        }

        $subtotalBeforeDiscount = $originalPrice * $quantity;
        $totalPriceDiscount = $priceDiscountPerUnit * $quantity;
        $subtotalAfterPriceDiscount = $finalPricePerUnit * $quantity;
        $finalSubtotal = $subtotalAfterPriceDiscount;
        $totalSavings = $totalPriceDiscount + $quantityDiscountAmount;

        return [
            'original_price' => $originalPrice,
            'final_price_per_unit' => $finalPricePerUnit,
            'price_discount_per_unit' => $priceDiscountPerUnit,
            'total_price_discount' => $totalPriceDiscount,
            'free_quantity' => $freeQuantity,
            'quantity_discount_amount' => $quantityDiscountAmount,
            'total_savings' => $totalSavings,
            'subtotal' => $finalSubtotal,
            'total_quantity' => $quantity + $freeQuantity,
            'price_promotion' => $pricePromotion,
            'quantity_promotion' => $quantityPromotion,
            'has_price_promotion' => $pricePromotion !== null,
            'has_quantity_promotion' => $quantityPromotion !== null,
            'has_multiple_promotions' => $pricePromotion !== null && $quantityPromotion !== null,
        ];
    }

    public function getTotalAvailableStockAttribute()
    {
        return $this->batches()
            ->available()
            ->notExpired()
            ->sum('quantity_remaining');
    }

    public function getNearestExpiryDateAttribute()
    {
        return $this->batches()
            ->available()
            ->notExpired()
            ->orderBy('expiry_date', 'asc')
            ->value('expiry_date');
    }

    public function reduceStockFEFO($quantityNeeded)
    {
        $batches = $this->batches()
            ->available()
            ->notExpired()
            ->FEFO()
            ->get();

        $remaining = $quantityNeeded;
        $usedBatches = [];

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $takeFromBatch = min($batch->quantity_remaining, $remaining);
            
            $batch->decrement('quantity_remaining', $takeFromBatch);
            
            $usedBatches[] = [
                'batch_id' => $batch->id,
                'expiry_date' => $batch->expiry_date,
                'quantity' => $takeFromBatch,
                'remaining_after' => $batch->quantity_remaining
            ];

            $remaining -= $takeFromBatch;
        }

        if ($remaining > 0) {
            throw new \Exception("Insufficient stock. Need {$remaining} more items.");
        }

        $this->stock = $this->total_available_stock;
        $this->save();

        return $usedBatches;
    }

    public function getHasExpiredBatchesAttribute()
    {
        return $this->batches()
            ->where('quantity_remaining', '>', 0)
            ->where('expiry_date', '<', now())
            ->exists();
    }

    public function getHasNearExpiryBatchesAttribute()
    {
        return $this->batches()
            ->where('quantity_remaining', '>', 0)
            ->where('expiry_date', '>=', now())
            ->where('expiry_date', '<=', now()->addDays(30))
            ->exists();
    }

    public function getHasH7ExpiryBatchesAttribute()
    {
        return $this->batches()
            ->where('quantity_remaining', '>', 0)
            ->where('expiry_date', '>=', now())
            ->where('expiry_date', '<=', now()->addDays(7))
            ->exists();
    }

    public function getNearestExpiryBatchAttribute()
    {
        return $this->batches()
            ->available()
            ->notExpired()
            ->orderBy('expiry_date', 'asc')
            ->first();
    }

    // Tambahkan di dalam class Product (sebelum closing bracket)

/**
 * Get barcode HTML
 */
public function getBarcodeHtml($width = 2, $height = 60)
{
    if (!$this->barcode) {
        return null;
    }
    
    return \Milon\Barcode\Facades\DNS1DFacade::getBarcodeHTML(
        $this->barcode, 
        'C128', 
        $width, 
        $height
    );
}

/**
 * Get barcode SVG
 */
public function getBarcodeSvg($width = 2, $height = 60)
{
    if (!$this->barcode) {
        return null;
    }
    
    return \Milon\Barcode\Facades\DNS1DFacade::getBarcodeSVG(
        $this->barcode, 
        'C128', 
        $width, 
        $height
    );
}

}