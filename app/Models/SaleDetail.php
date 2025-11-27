<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'original_price',
        'unit_price',
        'quantity',
        'price_promotion_id',
        'price_discount_amount',
        'price_promotion_type',
        'quantity_promotion_id',
        'free_quantity',
        'quantity_discount_amount',
        'quantity_promotion_type',
        'item_discount',
        'subtotal'
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'price_discount_amount' => 'decimal:2',
        'free_quantity' => 'integer',
        'quantity_discount_amount' => 'decimal:2',
        'item_discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function pricePromotion()
    {
        return $this->belongsTo(Promotion::class, 'price_promotion_id');
    }

    public function quantityPromotion()
    {
        return $this->belongsTo(Promotion::class, 'quantity_promotion_id');
    }

    // ✅ ACCESSORS
    public function getTotalQuantityAttribute()
    {
        // Total quantity termasuk free items
        return $this->quantity + $this->free_quantity;
    }

    public function getTotalDiscountAttribute()
    {
        return $this->price_discount_amount + $this->quantity_discount_amount + $this->item_discount;
    }

    public function getHasMultiplePromotionsAttribute()
    {
        return $this->price_promotion_id !== null && $this->quantity_promotion_id !== null;
    }

    public function getPromotionSummaryAttribute()
    {
        $summary = [];
        
        if ($this->price_promotion_id) {
            $summary[] = $this->price_promotion_type . ': -Rp' . number_format($this->price_discount_amount, 0, ',', '.');
        }
        
        if ($this->quantity_promotion_id) {
            $summary[] = 'Buy ' . $this->quantity . ' Get ' . $this->free_quantity . ' FREE';
        }
        
        return implode(' + ', $summary);
    }
}