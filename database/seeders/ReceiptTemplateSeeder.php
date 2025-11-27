<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReceiptTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Template untuk WhatsApp
        DB::table('receipt_templates')->insert([
            'type' => 'whatsapp',
            'name' => 'WhatsApp Receipt Template',
            'header_text' => "🛒 *SkyraMart - Digital Receipt*",
            'greeting_text' => "Dear *{customer_name}*,\nThank you for shopping with us! 💚",
            'transaction_section_title' => "📋 *Transaction Details*",
            'items_section_title' => "🛍️ *Order Summary*",
            'payment_section_title' => "💰 *Payment Details*",
            'footer_text' => "Thank you for your trust! 🙏\n_Please save this receipt for your records_",
            'contact_info' => "Need help? Contact us:\n📞 WA: 0895-3265-81316\n📍 Jl. Masjid Daruttaqwa No. 123, Depok",
            'notes_text' => "📄 *Download Your Receipt (PDF)*\n\nClick link below:\n{pdf_link}\n\n_Link valid for 7 days_",
            'store_branding' => '💚 *SkyraMart* - Your Trusted Store',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Template untuk Print (PDF)
        DB::table('receipt_templates')->insert([
            'type' => 'print',
            'name' => 'Print Receipt Template',
            'header_text' => "SkyraMart\nJl. Masjid Daruttaqwa No. 123\nDepok City, Indonesia\nWA: 0889-2114-416",
            'greeting_text' => null, // Print biasanya tanpa greeting
            'transaction_section_title' => "Transaction Info:",
            'items_section_title' => "Items Purchased:",
            'payment_section_title' => "Payment Details:",
            'footer_text' => "Thank You for Shopping!\nItems purchased are non-refundable\nPlease save this receipt for your records",
            'contact_info' => "Powered by SkyraMart POS System",
            'notes_text' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}