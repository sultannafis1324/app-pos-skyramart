<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            
            // Informasi metode pembayaran
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'ewallet', 'loyalty','qris']);
            $table->string('payment_channel')->nullable(); // contoh: BCA, Mandiri, OVO, DANA, dll
            $table->decimal('amount', 15, 2);
            $table->string('reference_number')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');

            // Data tambahan untuk integrasi midtrans
            $table->text('midtrans_qr_string')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->text('midtrans_snap_token')->nullable();
            $table->text('midtrans_payment_url')->nullable();
            $table->dateTime('expired_at')->nullable();

            // Tracking proses internal
            $table->boolean('stock_reduced')->default(false);   // true = stok sudah dikurangi
            $table->boolean('loyalty_deducted')->default(false); // true = poin loyalty sudah dikurangi

            // Relasi user (opsional, bisa null)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();

            // Index buat optimasi query
            $table->index(['sale_id', 'payment_method', 'status']);
            $table->index('midtrans_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
