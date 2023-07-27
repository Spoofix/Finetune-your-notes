<?php

namespace App\Jobs;

use App\Models\SpoofedDomain;
use App\Services\WhoIsSearch;
use App\Traits\RatingAlreadyScanned;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WhoIsRating implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RatingAlreadyScanned;

    private $spoofed_domain;
    public $tries = 5;
    /**
     * Create a new job instance.
     * @param $dt
     */
    public function __construct(SpoofedDomain $dt)
    {
        $this->spoofed_domain = $dt;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if( $this->copyRating($this->spoofed_domain,"WHOIS") ){
            return;
        }

        $screenshot = ('/assets/screenshots/' . $this->spoofed_domain->spoofed_domain . '.png');
        $output = WhoIsSearch::search($this->spoofed_domain->spoofed_domain);
        
        $this->spoofed_domain->registrar  = $output[1] ?? '';
        $this->spoofed_domain-> rating = $output[19] ?? '';
        $this->spoofed_domain-> screenshot= $screenshot ?? '';
        $this->spoofed_domain->country = $output[17] ?? '';
        $this->spoofed_domain->registrationDate = $output[5] ?? '';
        //new
        $this->spoofed_domain->domain  = $output[0] ?? '';
        $this->spoofed_domain->whois_server = $output[2] ?? '';
        $this->spoofed_domain->referral_url = $output[3] ?? '';
        $this->spoofed_domain->update_date  = $output[4] ?? '';
        $this->spoofed_domain->expiration_date  = $output[6] ?? '';
        $this->spoofed_domain->name_servers = $output[7] ?? '';
        $this->spoofed_domain->status  = $output[8] ?? '';
        $this->spoofed_domain->emails = $output[9] ?? ''; 
        $this->spoofed_domain->dnssec = $output[10] ?? '';
        $this->spoofed_domain->name = $output[11] ?? '';
        $this->spoofed_domain->org  = $output[12] ?? '';
        $this->spoofed_domain->address  = $output[13] ?? '';
        $this->spoofed_domain->city  = $output[14] ?? '';
        $this->spoofed_domain->state  = $output[15] ?? '';
        $this->spoofed_domain->registrant_postal_code = $output[16] ?? '';
//        Log::alert(json_encode($this->spoofed_domain));
        $this->spoofed_domain->save();
    }
}
