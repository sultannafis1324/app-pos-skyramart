<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->string('store_logo')->nullable();
            $table->string('store_email');
            $table->string('store_phone');
            $table->string('store_whatsapp');
            $table->text('store_address');
            $table->string('store_hours')->default('Monday - Sunday, 08:00 - 22:00');
            $table->string('store_website')->nullable();
            $table->string('store_instagram')->nullable();
            $table->string('store_facebook')->nullable();
            $table->text('store_description')->nullable();
            $table->string('currency_symbol')->default('Rp');
            $table->string('currency_code')->default('IDR');
            $table->string('timezone')->default('Asia/Jakarta');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};