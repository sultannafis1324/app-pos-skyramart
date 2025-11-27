<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMessageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'subject',
        'greeting_text',
        'account_details_title',
        'benefits_title',
        'benefits_list',
        'contact_info',
        'footer_text',
        'store_branding',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Available template variables
     */
    public static function getAvailableVariables()
    {
        return [
            '{customer_name}' => 'Customer Name',
            '{email}' => 'Customer Email',
            '{phone_number}' => 'Customer Phone Number',
            '{loyalty_points}' => 'Loyalty Points',
            '{store_name}' => 'Store Name',
            '{store_address}' => 'Store Address',
            '{store_whatsapp}' => 'Store WhatsApp',
            '{store_email}' => 'Store Email',
            '{store_hours}' => 'Store Operating Hours',
        ];
    }

    /**
     * Replace variables with real data
     */
    public function replaceVariables($text, $customer)
    {
        $replacements = [
            '{customer_name}' => $customer->customer_name ?? 'Valued Customer',
            '{email}' => $customer->email ?? '-',
            '{phone_number}' => $customer->phone_number ?? '-',
            '{loyalty_points}' => number_format($customer->loyalty_points ?? 0, 0, ',', '.'),
            '{store_name}' => 'SkyraMart',
            '{store_address}' => 'Jl. Masjid Daruttaqwa No. 123, Depok',
            '{store_whatsapp}' => '0889-2114-416',
            '{store_email}' => 'support@skyramart.com',
            '{store_hours}' => 'Monday - Sunday, 08:00 - 22:00',
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $text
        );
    }

    /**
     * Get active template by type
     */
    public static function getActive($type)
    {
        return self::where('type', $type)
            ->where('is_active', true)
            ->first();
    }

    public function scopeWhatsapp($query)
    {
        return $query->where('type', 'whatsapp');
    }

    public function scopeEmail($query)
    {
        return $query->where('type', 'email');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}