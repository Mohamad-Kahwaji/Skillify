<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE businesses MODIFY COLUMN status ENUM('pending','active','approved','rejected') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE businesses MODIFY COLUMN status ENUM('pending','active','rejected') NOT NULL DEFAULT 'pending'");
    }
};
