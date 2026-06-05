<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('approved')
                  ->after('is_active');

            $table->foreignId('business_id')
                  ->nullable()
                  ->constrained('businesses')
                  ->onDelete('set null')
                  ->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->dropColumn(['status', 'business_id']);
        });
    }
};
