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
       Schema::create('email_campaign_recipients', function (Blueprint $table) {
    $table->id();
     $table->foreignId('campaign_id')->constrained('email_campaigns')->cascadeOnDelete();
    $table->string('email');
    $table->string('name')->nullable();
    $table->enum('status', ['pending', 'sent', 'opened', 'clicked', 'failed', 'bounced'])->default('pending');
    $table->timestamp('sent_at')->nullable();
    $table->timestamp('opened_at')->nullable();
    $table->timestamp('clicked_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_campaign_recipients');
    }
};
