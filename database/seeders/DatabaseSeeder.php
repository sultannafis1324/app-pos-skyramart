<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        IndonesiaTableSeeder::class,
        UserSeeder::class,
        CategorySeeder::class,
        CustomerSeeder::class,
        SupplierSeeder::class,
        ProductSeeder::class,
        PromotionSeeder::class,
        ReceiptTemplateSeeder::class,
        CustomerMessageTemplateSeeder::class,
    ]);
}


}
