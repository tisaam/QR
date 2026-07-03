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
       Schema::create('plans', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // Free, Basic, Premium, Enterprise
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2)->default(0);
    $table->decimal('annual_price', 10, 2)->nullable();
    $table->unsignedInteger('trial_days')->default(0);
    $table->json('features')->nullable();
    $table->json('limits')->nullable(); // qr_limit, review_limit, etc.
    $table->enum('billing_cycle', ['monthly', 'yearly', 'one_time'])->default('monthly');
    $table->boolean('is_active')->default(true);
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});

// Plan Limits Structure (JSON)
// {
//     "qr_codes": 1,           // -1 for unlimited
//     "reviews_per_month": 20, // -1 for unlimited
//     "branches": 1,
//     "employees": 0,
//     "ai_credits": 0,
//     "analytics_days": 7,
//     "whatsapp": false,
//     "sms": false,
//     "nfc": false,
//     "white_label": false,
//     "custom_branding": false,
//     "remove_branding": false
// }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
