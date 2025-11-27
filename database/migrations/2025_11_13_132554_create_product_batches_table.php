<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('purchase_detail_id')->nullable()->constrained('purchase_details')->onDelete('set null');
            $table->date('expiry_date');
            $table->integer('quantity_received'); // Jumlah awal batch
            $table->integer('quantity_remaining'); // Sisa batch
            $table->string('batch_number')->nullable(); // Opsional untuk tracking
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'expiry_date', 'quantity_remaining']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};