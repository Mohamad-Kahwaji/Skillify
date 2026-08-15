<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moderation_warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('issuer_type')->nullable();
            $table->unsignedBigInteger('issuer_id')->nullable();
            $table->string('warningable_type');
            $table->unsignedBigInteger('warningable_id');
            $table->text('message');
            $table->timestamps();
            $table->index(['warningable_type', 'warningable_id']);
            $table->index(['issuer_type', 'issuer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moderation_warnings');
    }
};
