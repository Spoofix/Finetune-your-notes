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
            $table->string('screenshot');
            $table->string('country');
            $table->string('registrationDate');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spoofed_domains', function (Blueprint $table) {
            $table->dropColumn('screenshot');
            $table->dropColumn('country');
            $table->dropColumn('registrationDate');
           
        });
    }
};
