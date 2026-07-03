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
        Schema::create('review_reminders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('scan_id')->nullable()->constrained('qr_scans')->nullOnDelete();
    $table->string('customer_phone')->nullable();
    $table->string('customer_email')->nullable();
    $table->enum('channel', ['whatsapp', 'sms', 'email'])->default('whatsapp');
    $table->enum('status', ['pending', 'sent', 'converted', 'failed'])->default('pending');
    $table->timestamp('scheduled_at');
    $table->timestamp('sent_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_reminders');
    }
};
