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
        Schema::create('reported_spoof_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spoof_id')->nullable();
            $table->string('reportingName')->nullable();
            $table->foreignId('admin_id')->nullable();
            $table->foreignId('reported_via')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reported_spoof_domains');
    }
};
