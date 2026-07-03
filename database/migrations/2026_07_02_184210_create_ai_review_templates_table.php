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
        Schema::create('ai_review_templates', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->text('prompt_template');
    $table->enum('language', ['en', 'hi', 'gu'])->default('en');
    $table->unsignedTinyInteger('min_rating')->default(4);
    $table->unsignedTinyInteger('max_rating')->default(5);
    $table->boolean('is_active')->default(true);
    $table->boolean('is_default')->default(false);
    $table->integer('usage_count')->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_review_templates');
    }
};
