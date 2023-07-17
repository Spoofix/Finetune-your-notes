<?php

namespace App\Console\Commands;


use App\Models\Domain;
use App\Services\DnsTwist;
use App\Services\OpenSquat;
use App\Services\PulseDive;
use App\Models\SpoofedDomain;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScanSpoofingDomains extends Command
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
        DnsTwist::dnstwist();
        exit;
        $user_id = $this->option('user_id');
        $domain_id = $this->option('domain_id');

        $domain = Domain::query();

        if(!empty($user_id)){
            $domain = $domain->where('user_id',$user_id);
        }

        if(!empty($domain_id)){
            $domain = $domain->where('id',$domain_id);
        }

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
                    $output = pulseDive::search($spoofed_domain, 1);
                    // $rating = pulseDive::search($spoofed_domain, 1);
                    $rating = $output[0] ?? '';
                    $registrar = $output[1] ?? '';
                    $screenshot = $output[2] ?? '';
                    $country = $output[3] ?? '';
                    $registration = $output[4] ?? '';

                    array_push($fields_array,
                        [
                            'domain_id' => $domain->id,
                            'spoofed_domain' => $spoofed_domain,
                            'rating' => $rating,
                            'registrar' => $registrar,
                            'screenshot' => $screenshot,
                            'country' => $country,
                            'registrationDate' => $registration,
                            'last_batch' => $last_batch
                        ]
                    );
                }
                SpoofedDomain::insert($fields_array);
            }

            $domain->status = "SCANNED";
            $domain->save();
        }

    }
}
