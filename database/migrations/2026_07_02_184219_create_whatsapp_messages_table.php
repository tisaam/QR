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
       Schema::create('whatsapp_messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->string('customer_phone');
    $table->string('customer_name')->nullable();
    $table->string('message_type'); // review_request, reminder, thank_you
    $table->text('message_content');
    $table->string('template_id')->nullable();
    $table->enum('status', ['pending', 'sent', 'delivered', 'read', 'failed'])->default('pending');
    $table->string('whatsapp_message_id')->nullable();
    $table->json('response')->nullable();
    $table->timestamp('sent_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
