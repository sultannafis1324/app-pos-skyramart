<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'address_id',
        'phone_number',
        'email',
        'gender',
        'birth_date',
        'is_active',
        'photo_profile',
        'total_purchase',
        'loyalty_points',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
        'total_purchase' => 'decimal:2',
        'loyalty_points' => 'integer',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getGenderLabelAttribute()
    {
        return $this->gender === 'M' ? 'Male' : ($this->gender === 'F' ? 'Female' : null);
    }

    public function addPurchase($amount)
    {
        $this->increment('total_purchase', $amount);
        // Hitung loyalty points: setiap 100 rupiah = 1 poin
        $newPoints = floor($amount / 100);
        $this->increment('loyalty_points', $newPoints);
    }

    public function useLoyaltyPoints($points)
    {
        // Refresh data dulu untuk memastikan data terbaru
        $this->refresh();
        
        if ($this->loyalty_points >= $points) {
            $this->decrement('loyalty_points', $points);
            // Refresh lagi setelah update
            $this->refresh();
            return true;
        }
        return false;
    }

    // Method untuk sinkronisasi loyalty points berdasarkan total purchase
    public function syncLoyaltyPoints()
    {
        $expectedPoints = floor($this->total_purchase / 100);
        $this->update(['loyalty_points' => $expectedPoints]);
    }
}