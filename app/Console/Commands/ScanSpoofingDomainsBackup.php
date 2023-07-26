<?php

namespace App\Console\Commands;


use App\Models\Domain;
use App\Services\DnsTwist;
use App\Models\SpoofedDomain;
use App\Services\contentrating;
use App\Services\csscolor;
use App\Services\DomainSimilarity;
use App\Services\imagerating;
use App\Services\WhoIsSearch;
use App\Services\OpenSquat;
use App\Services\WhoIs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScanSpoofingDomainsBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scan-spoofing-domains {--user_id=} {--domain_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        $user_id = $this->option('user_id');
        $domain_id = $this->option('domain_id');

        $domain = Domain::query();

        if(!empty($user_id)){
            $domain = $domain->where('user_id',$user_id);
        }

        if(!empty($domain_id)){
            $domain = $domain->where('id',$domain_id);
        }
        // $fields_array = [];
        $domains = $domain->limit(10)->get();

        $last_batch=time(); // Will explain its purpose
        foreach ($domains as $domain) {
            $domain->status = "PROCESSING";
            $domain->save();

            $domain_ = trim($domain->domain_name);
            $domain__ = explode(".",$domain_);
            if( !empty($domain__[0]) ){
                $domain__[0] = trim($domain__[0]);

                $spoofed_domains = OpenSquat::find($domain__[0]);
                $fields_array = [];
                foreach ($spoofed_domains as $spoofed_domain) {
                    $todomainsimilarity = $domain_ . ", " . $spoofed_domain;
                    try{
                    $output = WhoIsSearch::search($spoofed_domain, 1) ;
                    $domainsimilarity = DomainSimilarity::rate($todomainsimilarity, 1) ;
                    $imagerating = imagerating::rate($todomainsimilarity, 1) ;
                    $contentrating = contentrating::rate($todomainsimilarity, 1) ;
                    $csscolor = csscolor::rate($todomainsimilarity, 1) ;
                  
                    }catch(\Exception $e)
                    {
                        info($e->getMessage());
                        continue;
                    }
                    // $rating = pulseDive::search($spoofed_domain, 1);
            //         $rating = $output[0] ?? '';
            //         $registrar = $output[1] ?? '';
            //         $screenshot = $output[2] ?? '';
            //         $country = $output[3] ?? '';
            //         $registration = $output[4] ?? '';
                        $domain_name  = $output[0] ?? '';
                        $registrar  = $output[1] ?? '';
                        $whois_server  = $output[2] ?? '';
                        $referral_url  = $output[3] ?? '';
                        $updated_date = $output[4] ?? '';
                        $creation_date  = $output[5] ?? '';
                        $expiration_date  = $output[6] ?? '';
                        $name_servers = $output[7] ?? '';
                        $status  = $output[8] ?? '';
                        $emails  = $output[9] ?? '';    
                        $dnssec  = $output[10] ?? '';
                        $name  = $output[11] ?? '';
                        $org  = $output[12] ?? '';
                        $address  = $output[13] ?? '';
                        $city  = $output[14] ?? '';
                        $state  = $output[15] ?? '';
                        $registrant_postal_code = $output[16] ?? '';
                        $country  = $output[17] ?? '';
                        $screenshot = $output[18] ?? '';
                        $rating = $output[19] ?? '';
                  

                    $fields_array=[];
                    array_push($fields_array,
                        [
                            'domain_id' => $domain->id,
                            'spoofed_domain' => $spoofed_domain,
                            'rating' => $rating,
                            'registrar' => $registrar,
                            'screenshot' => $screenshot,
                            'country' => $country,
                            'phashes' => $imagerating, //'imagerating' => $imagerating
                            'htmls' => $contentrating,  //'contentrating' => $contentrating,
                            'dir' => 'none',
                            'registrationDate' => $creation_date,
                            'last_batch' => $last_batch,
                            //new
                            'domain' => $domain_name,
                            'whois_server' => $whois_server,
                            'referral_url' => $referral_url,
                            'update_date' => $updated_date,
                            'expiration_date'=> $expiration_date,
                            'name_servers' => $name_servers,
                            'status' => $status,
                            'emails' => $emails,
                            'dnssec' => $dnssec,
                            'name' => $name,
                            'org' => $org,
                            'address' => $address,
                            'city' => $city,
                            'state' => $state,
                            'registrant_postal_code' => $registrant_postal_code,
                            'domainsimilarityrate' => $domainsimilarity ?? 'none',
                            'csscolor' => $csscolor
                           
                        ]
                    );
                    SpoofedDomain::insert($fields_array);
                    info(json_encode($fields_array));
                }
            }

                $outputs= DnsTwist::dnstwist($domain_, $last_batch);
              
                foreach ($outputs as $output) {
                    $todomainsimilarity = $domain_ . ", " . $outputs;   

                    $domainsimilarity = DomainSimilarity::rate($todomainsimilarity, 1) ;
                    // $imagerating = imagerating::rate($todomainsimilarity, 1) ;
                    // $contentrating = contentrating::rate($todomainsimilarity, 1) ;
                    $csscolor = csscolor::rate($todomainsimilarity, 1) ;
                    if($output[4] == 'none'){
                        continue;
                    }
                    if($output[1] == 100){
                        continue;
                    }
                    if($output[3] == 'none'){
                        continue;
                    }
                    try{
                        // $Data = WhoIs::search( $output[4], 1);
                        $Data = WhoIsSearch::search( $output[4], 1);
                        }catch(\Exception $e)
                        {
                            info($e->getMessage());
                            continue;
                        }
                    // /$data = pulseDive::search( $output[4], 1);
                        $domain_name  = $Data[0] ?? '';
                        $registrar  = $Data[1] ?? '';
                        $whois_server  = $Data[2] ?? '';
                        $referral_url  = $Data[3] ?? '';
                        $updated_date = $Data[4] ?? '';
                        $creation_date  = $Data[5] ?? '';
                        $expiration_date  = $Data[6] ?? '';
                        $name_servers = $Data[7] ?? '';
                        $status  = $Data[8] ?? '';
                        $emails  = $Data[9] ?? '';
                        $dnssec  = $Data[10] ?? '';
                        $name  = $Data[11] ?? '';
                        $org  = $Data[12] ?? '';
                        $address  = $Data[13] ?? '';
                        $city  = $Data[14] ?? '';
                        $state  = $Data[15] ?? '';
                        $registrant_postal_code = $Data[16] ?? '';
                        $country  = $Data[17] ?? '';
                        //
                    $fields_array = [];
                    $screenshotd = $output[0] ?? '';
                    Log::error($screenshotd);
                    $phashesd = $output[1] ?? '';
                    // $countryd = $output[2] ?? '';
                    $htmlsd = $output[3] ?? '';
                    $spoofed_domaind = $output[4] ?? '';
                    Log::error($spoofed_domaind);
                    $dird = $output[5] ?? '';
                    array_push($fields_array,
                    [
                        'domain_id' => $domain->id,
                        'spoofed_domain' => $spoofed_domaind,
                        'rating' => 'none',
                        'registrar' => $registrar,
                        'screenshot' => str_replace( public_path(),"",$screenshotd),
                        'country' => $country,//$countryd,
                        'phashes' => $phashesd,
                        'htmls' => $htmlsd,
                        'dir' => $dird,
                        'registrationDate' => $creation_date,
                        'last_batch' => $last_batch,
                        //new
                        'domain' => $domain_name,
                        'whois_server' => $whois_server,
                        'referral_url' => $referral_url,
                        'update_date' => $updated_date,
                        'expiration_date'=> $expiration_date,
                        'name_servers' => $name_servers,
                        'status' => $status,
                        'emails' => $emails,
                        'dnssec' => $dnssec,
                        'name' => $name,
                        'org' => $org,
                        'address' => $address,
                        'city' => $city,
                        'state' => $state,
                        'registrant_postal_code' => $registrant_postal_code,
                        'domainsimilarityrate' => $domainsimilarity ?? 'none',
                        'csscolor' => $csscolor
                    ]
                );
                    // log('hello');
                //   if(count($fields_array) > 0){
                    info(json_encode($fields_array));
                    SpoofedDomain::insert($fields_array);
                    // log('hello');
                // }
                }

              

        $domain->status = "SCANNED";
            $domain->save();
        }
    }
}
