<?php

namespace App\Traits;

use App\Models\SpoofedDomain;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait RatingAlreadyScanned{
    private function spoofExists($spoofed_domain,$select_values_to_update){
        return SpoofedDomain::where('spoofed_domain',$spoofed_domain->spoofed_domain)
            ->where('id','!=',$spoofed_domain->id)
            ->whereDate('created_at', Carbon::today()->toDateString())
            ->select($select_values_to_update)
            ->first();
    }

    public function copyRating($spoofed_domain,$rating){
        switch ($rating){
            case 'WHOIS':
                $select_values_to_update=['registrar','rating','screenshot','country',
                    'registrationDate','domain','whois_server','referral_url','update_date','expiration_date',
                    'name_servers','status','emails','dnssec','name','org','address','city','state','registrant_postal_code'];
                break;
            case 'DomainSimilarity':
                $select_values_to_update=["domainsimilarityrate"];
                break;
            case 'ContentRating':
                $select_values_to_update=["htmls"];
                break;
            case 'ImageRating':
                $select_values_to_update=["phashes"];
                break;
            case 'CssColor':
                $select_values_to_update=["csscolor"];
                break;

        }

        if(!isset($select_values_to_update)){
            Log::error("copyRating function call failed as the rating <".$rating."> is not found");
            return false;
        }

        $duplicate_spoof = $this->spoofExists($spoofed_domain, $select_values_to_update);
        if(!$duplicate_spoof){
            return false;
        }

        $spoofed_domain->update($duplicate_spoof);
        return true;
    }
}