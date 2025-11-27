<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama promo: "Promo Ramadhan", "Diskon Akhir Tahun"
            $table->string('code')->unique()->nullable(); // Kode kupon: DISKON50, RAMADHAN2024
            $table->text('description')->nullable();
            
            // Jenis promo
            $table->enum('type', [
                'percentage',  // Diskon Persentase (20%)
                'fixed',       // Diskon Nominal (Rp 10.000)
                'buy_x_get_y', // Beli 2 Gratis 1
                'bundle',      // Paket Hemat (2 produk jadi Rp X)
                'cashback',    // Cashback 10%
                'free_shipping', // Gratis Ongkir
                'seasonal'     // Event Musiman
            ])->default('percentage');
            
            // Nilai diskon
            $table->decimal('discount_value', 12, 2)->nullable(); // 20 (untuk 20% atau Rp 20.000)
            $table->decimal('max_discount', 12, 2)->nullable(); // Max diskon untuk percentage
            $table->decimal('min_purchase', 12, 2)->nullable(); // Min pembelian untuk aktifkan promo
            
            // Buy X Get Y
            $table->integer('buy_quantity')->nullable(); // Beli berapa
            $table->integer('get_quantity')->nullable(); // Gratis berapa
            
            // Bundle pricing
            $table->decimal('bundle_price', 12, 2)->nullable(); // Harga paket bundle
            $table->integer('bundle_quantity')->nullable(); // Jumlah item dalam bundle
            
            // Cashback
            $table->decimal('cashback_percentage', 5, 2)->nullable(); // Cashback %
            $table->decimal('max_cashback', 12, 2)->nullable(); // Max cashback amount
            
            // Tanggal aktif
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            
            // Batasan penggunaan
            $table->integer('usage_limit')->nullable(); // Total usage limit
            $table->integer('usage_per_customer')->nullable(); // Per customer limit
            $table->integer('current_usage')->default(0); // Tracking current usage
            
            // Target
            $table->enum('target_type', ['all', 'specific_products', 'category'])->default('all');
            $table->json('target_ids')->nullable(); // [1,2,3] untuk product_ids atau category_ids
            
            // Status & Priority
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // Promo dengan priority lebih tinggi diutamakan
            $table->boolean('is_stackable')->default(false); // Bisa digabung dengan promo lain?
            
            // Display settings
            $table->string('badge_text')->nullable(); // "HEMAT 50%", "BELI 2 GRATIS 1"
            $table->string('badge_color')->default('#FF0000'); // Warna badge
            $table->string('image')->nullable(); // Banner promo
            
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'start_date', 'end_date']);
            $table->index('code');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};