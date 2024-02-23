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
            $table->unsignedBigInteger('domain_id')->nullable();
            $table->string('spoofed_domain')->nullable();
            $table->integer('last_batch')->nullable();
            $table->string('rating')->nullable();
            $table->string('screenshot')->nullable();
            $table->string('country')->nullable();
            $table->string('registrationDate')->nullable();
            $table->string('registrar')->nullable();
            $table->string('phashes')->nullable();
            $table->string('htmls')->nullable();
            $table->string('dir')->nullable();
            $table->string('domain')->nullable();
            $table->string('whois_server')->nullable();
            $table->string('referral_url')->nullable();
            $table->string('update_date')->nullable();
            $table->string('expiration_date')->nullable();
            $table->string('name_servers')->nullable();
            $table->string('status')->nullable();
            $table->string('emails')->nullable();
            $table->string('dnssec')->nullable();
            $table->string('name')->nullable();
            $table->string('org')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('registrant_postal_code')->nullable();
            $table->string('domainsimilarityrate')->nullable();
            $table->string('csscolor')->nullable();
            $table->string('domain_rating')->nullable();
            $table->enum('spoof_status', ['SPOOFY', 'PHISHING', 'NOT_REPORTED', 'REPORTED'])->default('NOT_REPORTED');
            $table->string('ip_address')->nullable();
            $table->string('server_city')->nullable();
            $table->string('region')->nullable();
            $table->string('server_country')->nullable();
            $table->double('latitude', 10, 6)->nullable();
            $table->double('longitude', 10, 6)->nullable();
            $table->string('organization')->nullable();
            $table->string('isp')->nullable();
            $table->string('server_os')->nullable();
            $table->string('ssl_certificate_details')->nullable();
            $table->string('redirect_urls')->nullable();
            $table->string('http_status_code')->nullable();
            $table->string('cookies')->nullable();
            $table->longText('console_messages')->nullable();
            $table->string('security_headers')->nullable();
            $table->enum('spoof_status_new', ['scanned', 'inprogress', 'completed'])->default('scanned');
            $table->enum('current_scan_status', ['scanned', 'not_scanned'])->default('not_scanned');
            $table->enum('progress_status', ['new', 'monitoring', 'pending_authentication'])->default('new');
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
