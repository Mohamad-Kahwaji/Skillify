<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('whatsapp_number')->default('+963995227120');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('about_title')->default('عن منصة Skillify');
            $table->text('about_body')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
