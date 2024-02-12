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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->enum('all_notifications', ['true', 'false'])->default('true');
            $table->enum('takedown_requests', ['true', 'false'])->default('true');
            $table->enum('takedown_completed', ['true', 'false'])->default('true');
            $table->enum('news_and_updates', ['true', 'false'])->default('true');
            $table->enum('reminders', ['true', 'false'])->default('true');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
