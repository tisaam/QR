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
        Schema::create('qr_codes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('employee_id')->nullable()->constrained('users')->nullOnDelete();
    $table->string('name'); // e.g., "Table 5", "Room 101", "Reception"
    $table->string('slug')->unique();
    $table->enum('type', ['table', 'room', 'employee', 'counter', 'custom'])->default('custom');
    $table->string('identifier')->nullable(); // table number, room number
    $table->string('qr_image_path')->nullable();
    $table->string('qr_svg_path')->nullable();
    $table->string('landing_page_url');
    $table->unsignedInteger('scan_count')->default(0);
    $table->unsignedInteger('review_count')->default(0);
    $table->boolean('is_active')->default(true);
    $table->json('settings')->nullable(); // custom branding, colors
    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
