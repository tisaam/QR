<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->text('block_reason')->nullable()->after('status');
            $table->timestamp('blocked_at')->nullable()->after('block_reason');
        });

        DB::statement("ALTER TABLE businesses MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'rejected', 'blocked', 'suspended') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        // First revert any blocked/suspended records to pending
        DB::table('businesses')
            ->whereIn('status', ['blocked', 'suspended'])
            ->update(['status' => 'pending']);

        DB::statement("ALTER TABLE businesses MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'rejected') NOT NULL DEFAULT 'pending'");

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['block_reason', 'blocked_at']);
        });
    }
};