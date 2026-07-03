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
        Schema::create('qr_scans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('qr_code_id')->constrained()->cascadeOnDelete();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->string('session_id')->nullable();
    $table->string('ip_address')->nullable();
    $table->string('device_type')->nullable(); // mobile, desktop, tablet
    $table->string('browser')->nullable();
    $table->string('os')->nullable();
    $table->string('location')->nullable();
    $table->string('latitude')->nullable();
    $table->string('longitude')->nullable();
    $table->boolean('converted_to_review')->default(false);
    $table->timestamp('scanned_at');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_scans');
    }
};
