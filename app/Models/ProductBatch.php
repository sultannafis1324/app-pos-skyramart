<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'purchase_detail_id',
        'expiry_date',
        'quantity_received',
        'quantity_remaining',
        'batch_number',
        'notes'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'quantity_received' => 'integer',
        'quantity_remaining' => 'integer',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseDetail()
    {
        return $this->belongsTo(PurchaseDetail::class);
    }

    public function lossReports()
    {
        return $this->hasMany(LossReport::class, 'batch_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('quantity_remaining', '>', 0);
    }

    public function scopeNotExpired($query)
    {
        return $query->where('expiry_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeFEFO($query)
    {
        return $query->orderBy('expiry_date', 'asc');
    }

    // Accessors
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date < now();
    }

    public function getIsNearExpiryAttribute()
    {
        $daysUntilExpiry = now()->diffInDays($this->expiry_date, false);
        return $daysUntilExpiry >= 0 && $daysUntilExpiry <= 30;
    }

    public function getDaysUntilExpiryAttribute()
    {
        return now()->diffInDays($this->expiry_date, false);
    }
}