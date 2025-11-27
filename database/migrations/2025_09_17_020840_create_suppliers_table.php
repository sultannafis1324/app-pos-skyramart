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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->string('phone_number', 15)->nullable();
            $table->string('email')->nullable();
            $table->string('store_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('photo_profile')->nullable();
            $table->timestamps();

            $table->index('supplier_name');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
