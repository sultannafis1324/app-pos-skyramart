<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LossReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'batch_id',
        'batch_number',
        'expiry_date',
        'quantity_expired',
        'purchase_price',
        'total_loss',
        'notes',
        'recorded_by'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'quantity_expired' => 'integer',
        'purchase_price' => 'decimal:2',
        'total_loss' => 'decimal:2',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batch()
    {
        return $this->belongsTo(ProductBatch::class, 'batch_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}