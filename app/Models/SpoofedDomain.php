<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpoofedDomain extends Model
{
    use HasFactory;

    protected $table = "spoofed_domains";

    protected $fillable = [
        'domain_id', 'spoofed_domain', 'rating', 'last_batch', 'registrar', 'screenshot', 'phashes', 'htmls', 'dir', 'country', 'registrationDate', //new 
        'domain', 'whois_server', 'referral_url', 'update_date', 'expiration_date', 'name_servers', 'status', 'emails', 'dnssec', 'name', 'org', 'address', 'city', 'state', 'registrant_postal_code', //new
        'csscolor', 'domainsimilarityrate', //new
        'domain_rating', 'spoof_status', //new
        'ip_address', 'server_city', 'region', 'server_country', 'latitude', 'longitude', 'organization', 'isp',
        'server_os', 'ssl_certificate_details', 'redirect_urls', 'http_status_code', 'cookies', 'console_messages', 'security_headers',
        'spoof_status','norink'
    ];

    public static function processingDomains()
    {
        $columns = self::columnsToCheck();

        $this_ = null;
        foreach ($columns as $column) {
            $this_ = self::whereRaw($column . " = 'processing'");
        }

        return $this_;
    }

    public static function validDomains()
    {
        $columns = self::columnsToCheck();

        $this_ = null;
        foreach ($columns as $column) {
            $this_ = self::whereRaw($column . " != 'none'")->whereRaw($column . " IS NOT NULL");
        }

        return $this_;
    }

    private static function columnsToCheck()
    {
        return ['phashes', 'htmls', 'domain_rating', 'csscolor', 'domainsimilarityrate'];
    }
}
