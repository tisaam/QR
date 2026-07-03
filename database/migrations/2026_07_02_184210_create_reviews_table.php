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
        Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('qr_code_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('scan_id')->nullable()->constrained('qr_scans')->nullOnDelete();
    $table->foreignId('employee_id')->nullable()->constrained('users')->nullOnDelete();
    $table->unsignedTinyInteger('rating'); // 1-5
    $table->text('review_text')->nullable();
    $table->text('ai_suggested_review')->nullable();
    $table->enum('source', ['qr_scan', 'whatsapp', 'sms', 'email', 'nfc', 'direct'])->default('qr_scan');
    $table->enum('status', ['pending', 'published', 'failed', 'skipped'])->default('pending');
    $table->string('google_review_id')->nullable();
    $table->string('customer_name')->nullable();
    $table->string('customer_phone')->nullable();
    $table->string('customer_email')->nullable();
    $table->boolean('is_returning_customer')->default(false);
    $table->json('metadata')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
