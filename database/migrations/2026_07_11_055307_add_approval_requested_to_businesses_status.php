<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE businesses MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'approval_requested', 'rejected', 'blocked', 'suspended') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::table('businesses')
            ->where('status', 'approval_requested')
            ->update(['status' => 'pending']);

        DB::statement("ALTER TABLE businesses MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'rejected', 'blocked', 'suspended') NOT NULL DEFAULT 'pending'");
    }
};