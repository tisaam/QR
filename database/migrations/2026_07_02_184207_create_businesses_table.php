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
       Schema::create('businesses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('address');
    $table->string('city');
    $table->string('state');
    $table->string('pincode');
    $table->string('phone');
    $table->string('email');
    $table->string('website')->nullable();
    $table->string('google_place_id')->nullable();
    $table->string('google_review_link')->nullable();
    $table->string('logo')->nullable();
    $table->string('cover_image')->nullable();
    $table->enum('business_type', ['restaurant', 'hotel', 'retail', 'salon', 'hospital', 'other'])->default('other');
    $table->enum('status', ['active', 'inactive', 'pending', 'rejected'])->default('pending');
    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
