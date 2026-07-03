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
        Schema::create('employee_performances', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->date('date');
    $table->unsignedInteger('qr_scans')->default(0);
    $table->unsignedInteger('reviews_generated')->default(0);
    $table->decimal('avg_rating', 3, 2)->default(0);
    $table->unsignedInteger('positive_reviews')->default(0);
    $table->unsignedInteger('negative_reviews')->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_performances');
    }
};
