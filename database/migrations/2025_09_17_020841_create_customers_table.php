<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->string('phone_number', 15)->nullable();
            $table->string('bank_name', 15)->nullable();
            $table->string('email')->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->date('birth_date')->nullable();
            $table->decimal('total_purchase', 15, 2)->default(0);
            $table->integer('loyalty_points')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('photo_profile')->nullable();
            $table->timestamps();
            
            $table->index('customer_name');
            $table->index('phone_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
