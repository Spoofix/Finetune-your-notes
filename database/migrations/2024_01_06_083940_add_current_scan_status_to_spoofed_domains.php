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
            $table->enum('current_scan_status', ['scanned', 'not_scanned'])->default('not_scanned');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spoofed_domains', function (Blueprint $table) {
            $table->dropColumn('current_scan_status');
        });
    }
};
