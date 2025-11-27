<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'address_id',
        'phone_number',
        'email',
        'store_name',
        'is_active',
        'photo_profile',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }


    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Di Supplier.php model
public function getNameAttribute()
{
    return $this->supplier_name;
}
}