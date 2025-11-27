<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'header_text',
        'greeting_text',
        'transaction_section_title',
        'items_section_title',
        'payment_section_title',
        'footer_text',
        'contact_info',
        'store_branding',
        'notes_text',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * ✅ Available template variables (TIDAK BISA DIUBAH)
     */
    public static function getAvailableVariables($type)
    {
        $commonVars = [
            '{customer_name}' => 'Customer Name',
            '{transaction_number}' => 'Transaction Number',
            '{date}' => 'Transaction Date',
            '{time}' => 'Transaction Time',
            '{cashier_name}' => 'Cashier Name',
            '{items}' => 'List of Items (auto-generated)',
            '{subtotal}' => 'Subtotal Amount',
            '{discount}' => 'Discount Amount',
            '{discount_percentage}' => 'Discount Percentage',
            '{tax}' => 'Tax Amount',
            '{total}' => 'Total Amount',
            '{payment_method}' => 'Payment Method',
            '{payment_channel}' => 'Payment Channel/Bank',
        ];

        if ($type === 'whatsapp') {
            return array_merge($commonVars, [
                '{pdf_link}' => 'PDF Download Link',
                '{store_whatsapp}' => 'Store WhatsApp Number',
            ]);
        }

        return $commonVars;
    }

    /**
     * ✅ Replace variables dengan data real
     */
    public function replaceVariables($text, $data)
    {
        $replacements = [
            '{customer_name}' => $data['customer_name'] ?? 'Walk-in Customer',
            '{transaction_number}' => $data['transaction_number'] ?? '-',
            '{date}' => $data['date'] ?? '-',
            '{time}' => $data['time'] ?? '-',
            '{cashier_name}' => $data['cashier_name'] ?? '-',
            '{subtotal}' => 'Rp ' . number_format($data['subtotal'] ?? 0, 0, ',', '.'),
            '{discount}' => 'Rp ' . number_format($data['discount'] ?? 0, 0, ',', '.'),
            '{discount_percentage}' => number_format($data['discount_percentage'] ?? 0, 1) . '%',
            '{tax}' => 'Rp ' . number_format($data['tax'] ?? 0, 0, ',', '.'),
            '{total}' => 'Rp ' . number_format($data['total'] ?? 0, 0, ',', '.'),
            '{payment_method}' => strtoupper($data['payment_method'] ?? 'CASH'),
            '{payment_channel}' => $data['payment_channel'] ?? '-',
            '{pdf_link}' => $data['pdf_link'] ?? '',
            '{store_whatsapp}' => '0889-2114-416',
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $text
        );
    }

    /**
     * ✅ Get active template by type
     */
    public static function getActive($type)
    {
        return self::where('type', $type)
            ->where('is_active', true)
            ->first();
    }

    /**
     * ✅ Scopes
     */
    public function scopeWhatsapp($query)
    {
        return $query->where('type', 'whatsapp');
    }

    public function scopePrint($query)
    {
        return $query->where('type', 'print');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}