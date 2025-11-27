<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class StoreSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'store_logo',
        'store_email',
        'store_phone',
        'store_whatsapp',
        'store_address',
        'store_hours',
        'store_website',
        'store_instagram',
        'store_facebook',
        'store_description',
        'currency_symbol',
        'currency_code',
        'timezone',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active store settings (dengan caching)
     */
    public static function getActive()
    {
        return Cache::remember('store_settings', 3600, function () {
            return self::where('is_active', true)->first();
        });
    }

    /**
     * Clear cache when updating
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('store_settings');
        });

        static::deleted(function () {
            Cache::forget('store_settings');
        });
    }

    /**
     * Get store variables for template replacement
     */
    public function getVariables()
    {
        return [
            '{store_name}' => $this->store_name,
            '{store_email}' => $this->store_email,
            '{store_phone}' => $this->store_phone,
            '{store_whatsapp}' => $this->store_whatsapp,
            '{store_address}' => $this->store_address,
            '{store_hours}' => $this->store_hours,
            '{store_website}' => $this->store_website ?? '',
            '{store_instagram}' => $this->store_instagram ?? '',
            '{store_facebook}' => $this->store_facebook ?? '',
            '{store_description}' => $this->store_description ?? '',
            '{currency_symbol}' => $this->currency_symbol,
        ];
    }

    /**
     * Replace variables in text
     */
    public function replaceVariables($text)
    {
        return str_replace(
            array_keys($this->getVariables()),
            array_values($this->getVariables()),
            $text
        );
    }
}