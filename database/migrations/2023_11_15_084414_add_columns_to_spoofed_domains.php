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
            $table->string('ip_address');
            $table->string('server_city');
            $table->string('region');
            $table->string('server_country');
            $table->double('latitude', 10, 6);
            $table->double('longitude', 10, 6);
            $table->string('organization');
            $table->string('isp');
            $table->string('server_os');
            $table->string('ssl_certificate_details');
            $table->string('redirect_urls');
            $table->string('http_status_code');
            $table->string('cookies');
            $table->longText('console_messages');
            $table->string('security_headers');
            $table->enum('spoof_status_new', ['scanned', 'inprogress', 'completed'])->default('scanned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spoofed_domains', function (Blueprint $table) {
            $table->dropColumn('ip_address');
            $table->dropColumn('city');
            $table->dropColumn('region');
            $table->dropColumn('server_country');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('organization');
            $table->dropColumn('isp');
            $table->dropColumn('server_os');
            $table->dropColumn('ssl_certificate_details');
            $table->dropColumn('redirect_urls');
            $table->dropColumn('http_status_code');
            $table->dropColumn('cookies');
            $table->dropColumn('console_messages');
            $table->dropColumn('security_headers');
        });
    }
};
