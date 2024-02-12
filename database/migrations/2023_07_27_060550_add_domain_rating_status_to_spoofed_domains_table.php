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
        Schema::table('spoofed_domains', function (Blueprint $table) {
            $table->string('domain_rating');
            $table->enum('spoof_status',['SPOOFY','PHISHING','NOT_REPORTED','REPORTED'])->default('NOT_REPORTED');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spoofed_domains', function (Blueprint $table) {
            $table->dropColumn('domain_rating');
            // $table->dropColumn('status',['SPOOFY','PHISHING','NOT_REPORTED','REPORTED'])->default('NOT_REPORTED');
        });
    }
};
