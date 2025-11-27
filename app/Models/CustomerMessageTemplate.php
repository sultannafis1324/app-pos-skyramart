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
            '{store_name}' => 'Store Name (from Store Settings)',
            '{store_address}' => 'Store Address (from Store Settings)',
            '{store_whatsapp}' => 'Store WhatsApp (from Store Settings)',
            '{store_phone}' => 'Store Phone (from Store Settings)',
            '{store_email}' => 'Store Email (from Store Settings)',
            '{store_hours}' => 'Store Operating Hours (from Store Settings)',
            '{store_website}' => 'Store Website (from Store Settings)',
            '{currency_symbol}' => 'Currency Symbol (from Store Settings)',
        ];
    }

    /**
     * Replace variables with real data
     */
    public function replaceVariables($text, $customer)
    {
        // Get store settings
        $store = StoreSetting::getActive();

        $replacements = [
            '{customer_name}' => $customer->customer_name ?? 'Valued Customer',
            '{email}' => $customer->email ?? '-',
            '{phone_number}' => $customer->phone_number ?? '-',
            '{loyalty_points}' => number_format($customer->loyalty_points ?? 0, 0, ',', '.'),
        ];

        // Merge with store variables
        if ($store) {
            $replacements = array_merge($replacements, $store->getVariables());
        }

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $text
        );
    }

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