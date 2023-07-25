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
            $table->string('domain');
            $table->string('whois_server');
            $table->string('referral_url');
            $table->string('update_date');
            $table->string('expiration_date');
            $table->string('name_servers');
            $table->string('status');
            $table->string('emails');
            $table->string('dnssec');
            $table->string('name');
            $table->string('org');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('registrant_postal_code');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spoofed_domains', function (Blueprint $table) {
            $table->dropColumn('domain');
            $table->dropColumn('whois_server');
            $table->dropColumn('referral_url');
            $table->dropColumn('update_date');
            $table->dropColumn('expiration_date');
            $table->dropColumn('name_servers');
            $table->dropColumn('status');
            $table->dropColumn('emails');
            $table->dropColumn('dnssec');
            $table->dropColumn('name');
            $table->dropColumn('org');
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('registrant_postal_code');
          
        });
    }
};
