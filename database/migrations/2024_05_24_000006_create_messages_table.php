<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('message_text');
            $table->boolean('is_read')->default(false);
            $table->timestamp('send_date')->nullable();
            $table->string('file_path')->nullable();      // مسار الملف
            $table->string('file_name')->nullable();      // اسم الملف الأصلي
            $table->string('file_type')->nullable();      // image / pdf / video ...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
