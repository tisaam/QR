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
       Schema::create('subscriptions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('plan_id')->constrained();
    $table->foreignId('business_id')->nullable()->constrained()->nullOnDelete();
    $table->string('razorpay_subscription_id')->nullable();
    $table->string('razorpay_payment_id')->nullable();
    $table->enum('status', ['active', 'expired', 'cancelled', 'past_due', 'trialing'])->default('active');
    $table->timestamp('starts_at');
    $table->timestamp('ends_at')->nullable();
    $table->timestamp('trial_ends_at')->nullable();
    $table->timestamp('cancelled_at')->nullable();
    $table->json('features')->nullable();
    $table->json('limits_used')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
