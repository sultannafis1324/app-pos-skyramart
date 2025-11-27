<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreSetting;

class StoreSettingSeeder extends Seeder
{
    public function run(): void
    {
        StoreSetting::create([
            'store_name' => 'SkyraMart',
            'store_email' => 'support@skyramart.com',
            'store_phone' => '0889-2114-416',
            'store_whatsapp' => '0889-2114-416',
            'store_address' => 'Jl. Masjid Daruttaqwa No. 123, Depok City, West Java, Indonesia',
            'store_hours' => 'Monday - Sunday, 08:00 - 22:00',
            'store_website' => 'https://www.skyramart.com',
            'store_instagram' => '@skyramart',
            'store_facebook' => 'facebook.com/skyramart',
            'store_description' => 'Your trusted neighborhood store for quality products and excellent service. We pride ourselves on providing fresh goods and friendly customer service.',
            'currency_symbol' => 'Rp',
            'currency_code' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'is_active' => true,
        ]);
    }
}