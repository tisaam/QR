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
        Schema::create('nfc_cards', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('qr_code_id')->nullable()->constrained()->nullOnDelete();
    $table->string('card_uid')->unique();
    $table->string('name');
    $table->string('design')->nullable();
    $table->enum('status', ['active', 'inactive', 'lost'])->default('active');
    $table->unsignedInteger('tap_count')->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nfc_cards');
    }
};
