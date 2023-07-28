<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpoofedDomain extends Model
{
    use HasFactory;

    protected $table="spoofed_domains";

    protected $fillable = [
        'domain_id', 'spoofed_domain', 'rating' ,'last_batch', 'registrar', 'screenshot', 'phashes', 'htmls', 'dir', 'country', 'registrationDate', //new 
        'domain', 'whois_server', 'referral_url', 'update_date', 'expiration_date', 'name_servers', 'status', 'emails', 'dnssec', 'name', 'org', 'address', 'city', 'state', 'registrant_postal_code', //new
        'csscolor', 'domainsimilarityrate', //new
        'domain_rating','spoof_status'
    ];

    public static function validDomains(){
        $columns = ['phashes'];
        
        $this_=self::whereRaw("1=1");
        // foreach($columns as $column){
        //  $this_=self::whereNotIn($column,['none',NULL]);
        // }

        return $this_;
    }
}
