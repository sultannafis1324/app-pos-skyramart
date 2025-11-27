<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->dateTime('sale_date');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('discount_percentage', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2);
            
            // PENTING: Status determines apakah stock sudah dikurangi atau belum
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('transaction_number');
            $table->index('sale_date');
            $table->index('status'); // Index untuk filter
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};