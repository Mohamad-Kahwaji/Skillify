<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('reportable_type')->nullable()->after('post_id');
            $table->unsignedBigInteger('reportable_id')->nullable()->after('reportable_type');
            $table->index(['reportable_type', 'reportable_id']);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('post_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex(['reportable_type', 'reportable_id']);
            $table->dropColumn(['reportable_type', 'reportable_id']);
            $table->foreignId('post_id')->nullable(false)->change();
        });
    }
};
