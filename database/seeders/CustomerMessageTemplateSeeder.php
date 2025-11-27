<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerMessageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // WhatsApp Template
        DB::table('customer_message_templates')->insert([
            'type' => 'whatsapp',
            'name' => 'WhatsApp Welcome Message',
            'subject' => null,
            'greeting_text' => "🎉 *Welcome to {store_name}!* 🎉\n\nHello *{customer_name}*! 👋\n\nThank you for joining the SkyraMart family! 💚",
            'account_details_title' => "📋 *Your Account Details*",
            'benefits_title' => "🌟 *Member Benefits*",
            'benefits_list' => "✅ *Loyalty Rewards Program*\n   Earn 1 point per Rp 100 spent!\n\n✅ *Exclusive Promotions*\n   Get special deals & discounts\n\n✅ *Digital Receipts*\n   Instant receipts via WhatsApp & Email\n\n✅ *Easy Shopping*\n   Fast checkout & order tracking",
            'contact_info' => "🏪 {store_address}\n📱 WhatsApp: {store_whatsapp}\n📧 Email: {store_email}\n⏰ Hours: {store_hours}",
            'footer_text' => "Start shopping now and enjoy amazing deals! 🛒",
            'store_branding' => "💚 *{store_name}* - Your Trusted Store\n_Thank you for choosing us!_",
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Email Template
        DB::table('customer_message_templates')->insert([
            'type' => 'email',
            'name' => 'Email Welcome Message',
            'subject' => 'Welcome to {store_name}! 🎉',
            'greeting_text' => "Dear {customer_name},\n\nWe are thrilled to have you as our valued customer! Your account has been successfully created and you're now part of the SkyraMart family.",
            'account_details_title' => "Your Account Information:",
            'benefits_title' => "Member Benefits:",
            'benefits_list' => json_encode([
                [
                    'icon' => '🎁',
                    'title' => 'Loyalty Rewards Program',
                    'description' => 'Earn 1 point for every Rp 100 you spend! Redeem points for exclusive discounts.'
                ],
                [
                    'icon' => '💰',
                    'title' => 'Exclusive Promotions',
                    'description' => 'Get access to special deals, seasonal promotions, and member-only discounts.'
                ],
                [
                    'icon' => '🛍️',
                    'title' => 'Easy Shopping Experience',
                    'description' => 'Fast checkout, order history tracking, and personalized recommendations.'
                ],
                [
                    'icon' => '📱',
                    'title' => 'Digital Receipts',
                    'description' => 'Get instant receipts via email and WhatsApp for every purchase.'
                ]
            ]),
            'contact_info' => "Visit us: {store_address}\nWhatsApp: {store_whatsapp}\nEmail: {store_email}\nStore Hours: {store_hours}",
            'footer_text' => "We look forward to serving you and making your shopping experience exceptional!\n\nHappy Shopping! 🛒",
            'store_branding' => "{store_name} - Your Trusted Store",
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}