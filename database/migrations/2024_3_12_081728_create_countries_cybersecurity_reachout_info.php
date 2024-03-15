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
        Schema::create('countries_cybersecurity_reachout_info', function (Blueprint $table) {
            $table->id();
            $table->string("country")->nullable();
            $table->string("organization")->nullable();
            $table->string("url")->nullable();
            $table->string("email")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries_cybersecurity_reachout_info');
    }
};
