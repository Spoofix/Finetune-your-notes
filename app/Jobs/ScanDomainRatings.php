<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Domain;
use App\Services\CssColor;
use App\Models\SpoofedDomain;
use App\Services\ImageRating;
use Illuminate\Bus\Queueable;
use App\Services\DomainRating;
use App\Services\ContentRating;
use App\Services\DomainSimilarity;
use Illuminate\Support\Facades\Log;
use App\Traits\RatingAlreadyScanned;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use function NunoMaduro\Collision\Exceptions\getClassName;

class ScanDomainRatings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RatingAlreadyScanned;

    private $ratings = [
        DomainSimilarity::class,
        ContentRating::class,
        ImageRating::class,
        CssColor::class,
        DomainRating::class
    ];

    private $spoofed_domain;
    public $tries = 5;
    public $tag;
    /**
     * Create a new job instance.
     * @param $dt
     */
    public function __construct(SpoofedDomain $dt)
    {
        $this->spoofed_domain = $dt;
        $this->tag = "ScanDomains".$dt->domain_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->ratings as $rating){

            if( $this->copyRating($this->spoofed_domain,str_replace("App\Services\\","", $rating))) {
                return;
            }

            try {
                $domain = Domain::find($this->spoofed_domain->domain_id);
                $todomainsimilarity = $domain->domain_name . ", " . $this->spoofed_domain->spoofed_domain;
                Log::info("Started scanning < ".$rating."> for domain < ". $todomainsimilarity."> started");
                $this->spoofed_domain->{$rating::dbColumnName()} = $rating::rate($todomainsimilarity);
            }catch (\Exception $exception){
                Log::error("Obtaining ".$rating::dbColumnName()." ratings for ".$this->spoofed_domain->spoofed_domain." failed: ".$exception->getMessage()." <>".$exception->getTraceAsString());
                $this->spoofed_domain->{$rating::dbColumnName()} = "failed";
            }

            Log::info("Started scanning < ".$rating."> for domain < ". $todomainsimilarity.">  completed");
            $this->spoofed_domain->save();
        }
    }

    public function toArray()
    {
        return [
            'tag' => $this->tag,
            // Add other properties here if needed
        ];
    }
}
