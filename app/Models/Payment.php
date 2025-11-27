<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'payment_method',
        'payment_channel',
        'amount',
        'reference_number',
        'midtrans_order_id',
        'midtrans_snap_token',
        'midtrans_payment_url',
        'midtrans_qr_string',
        'bank_name',
        'payment_date',
        'expired_at',
        'status',
        'stock_reduced',
        'loyalty_deducted',
        'user_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'expired_at' => 'datetime',
        'stock_reduced' => 'boolean',
        'loyalty_deducted' => 'boolean',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
    
    /**
 * Check if payment is expired
 */
public function isExpired(): bool
{
    if ($this->status !== 'pending' || !$this->expired_at) {
        return false;
    }
    
    return $this->expired_at < now();
}

/**
 * Scope untuk cek payment yang expired
 */
public function scopeExpired($query)
{
    return $query->where('status', 'pending')
        ->whereNotNull('expired_at')
        ->where('expired_at', '<', now());
}

    public function getPaymentMethodLabelAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'card' => 'Credit/Debit Card',
            'transfer' => 'Bank Transfer',
            'ewallet' => 'E-Wallet',
            'loyalty' => 'Loyalty Points',
            'qris' => 'QRIS',
            default => 'Unknown'
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            default => 'Unknown'
        };
    }
    
    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'completed' => 'green',
            'failed' => 'red',
            default => 'gray'
        };
    }
}