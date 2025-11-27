<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
    'purchase_id',
    'product_id',
    'purchase_price',
    'quantity',
    'expiry_date', // ✅ TAMBAHKAN
    'subtotal'
];

protected $casts = [
    'purchase_price' => 'decimal:2',
    'quantity' => 'integer',
    'expiry_date' => 'date', // ✅ TAMBAHKAN
    'subtotal' => 'decimal:2',
];

// ✅ TAMBAHKAN relationship
public function batch()
{
    return $this->hasOne(ProductBatch::class);
}
    // Relationships
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getTotalPriceAttribute()
    {
        return $this->purchase_price * $this->quantity;
    }
}