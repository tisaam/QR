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
        Schema::create('sms_messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->string('customer_phone');
    $table->string('customer_name')->nullable();
    $table->text('message_content');
    $table->enum('status', ['pending', 'sent', 'delivered', 'failed'])->default('pending');
    $table->string('provider_message_id')->nullable();
    $table->decimal('cost', 8, 4)->default(0);
    $table->timestamp('sent_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_messages');
    }
};
