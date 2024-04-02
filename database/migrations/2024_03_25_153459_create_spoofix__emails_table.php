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
        Schema::create('spoofix__emails', function (Blueprint $table) {
            $table->id();
            $table->integer('index')->nullable();
            $table->string('in_reply_to')->nullable();
            $table->string('references')->nullable();
            $table->string('from_address')->nullable();
            $table->string('subject')->nullable();
            $table->string('date')->nullable();
            $table->string('message_id')->nullable();
            $table->longText('body')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spoofix__emails');
    }
};
