<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // decimal(8,2) max = 999,999.99 — too small for SYP prices
            // decimal(12,2) max = 9,999,999,999.99
            $table->decimal('price', 12, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->change();
        });
    }
};
