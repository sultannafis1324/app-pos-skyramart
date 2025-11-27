<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // ✅ MULTI-PROMOTION SUPPORT
            $table->foreignId('price_promotion_id')->nullable()->constrained('promotions')->onDelete('set null');
            $table->foreignId('quantity_promotion_id')->nullable()->constrained('promotions')->onDelete('set null');

            $table->string('product_name'); // snapshot nama produk
            $table->decimal('original_price', 12, 2); // harga asli sebelum semua diskon
            $table->decimal('unit_price', 12, 2); // harga final per unit (setelah price discount)
            $table->integer('quantity'); // jumlah dibeli (tidak termasuk free items)
            
            // ✅ PRICE DISCOUNT TRACKING
            $table->decimal('price_discount_amount', 12, 2)->default(0); // diskon dari percentage/fixed
            $table->string('price_promotion_type')->nullable(); // percentage, fixed, dll
            
            // ✅ QUANTITY DISCOUNT TRACKING
            $table->integer('free_quantity')->default(0); // jumlah gratis dari buy_x_get_y
            $table->decimal('quantity_discount_amount', 12, 2)->default(0); // nilai diskon dari free items
            $table->string('quantity_promotion_type')->nullable(); // buy_x_get_y
            
            // MANUAL DISCOUNT (optional additional discount by cashier)
            $table->decimal('item_discount', 12, 2)->default(0);
            
            // TOTAL
            $table->decimal('subtotal', 12, 2); // total setelah semua diskon
            $table->timestamps();

            $table->index(['sale_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};