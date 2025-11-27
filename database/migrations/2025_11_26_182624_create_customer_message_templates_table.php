<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_message_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['whatsapp', 'email'])->unique();
            $table->string('name');
            $table->text('subject')->nullable(); // For email only
            $table->text('greeting_text')->nullable();
            $table->text('account_details_title')->nullable();
            $table->text('benefits_title')->nullable();
            $table->text('benefits_list')->nullable(); // JSON or text
            $table->text('contact_info')->nullable();
            $table->text('footer_text')->nullable();
            $table->text('store_branding')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_message_templates');
    }
};