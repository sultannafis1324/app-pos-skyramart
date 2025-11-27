<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('receipt_templates', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique(); // 'whatsapp' atau 'print'
            $table->string('name'); // Nama template (misal: "WhatsApp Receipt Template")
            $table->text('header_text')->nullable(); // Teks header (nama toko, alamat)
            $table->text('greeting_text')->nullable(); // Sapaan awal
            $table->text('transaction_section_title')->nullable(); // Judul section transaksi
            $table->text('items_section_title')->nullable(); // Judul section items
            $table->text('payment_section_title')->nullable(); // Judul section payment
            $table->text('footer_text')->nullable(); // Teks penutup/terima kasih
            $table->text('contact_info')->nullable(); // Info kontak (WA, alamat)
            $table->text('notes_text')->nullable(); // Catatan tambahan
            $table->text('store_branding')->nullable(); // Brand text di akhir (contoh: "💚 SkyraMart - Your Trusted Store")
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_templates');
    }
};