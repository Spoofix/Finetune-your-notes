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
            $table->enum('progress_status', ['new', 'monitoring', 'pending_authentication'])->default('new');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spoofed_domains', function (Blueprint $table) {
            $table->dropColumn('progress_status');
        });
    }
};
