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
        Schema::create('spoofed_domains', function (Blueprint $table) {
            $table->id();
            $table->integer('domain_id');
            $table->string('spoofed_domain');
            $table->integer("last_batch");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spoofed_domains');
    }
};
