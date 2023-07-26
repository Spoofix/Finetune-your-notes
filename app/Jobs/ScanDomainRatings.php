<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Models\SpoofedDomain;
use App\Services\ContentRating;
use App\Services\CssColor;
use App\Services\DomainSimilarity;
use App\Services\ImageRating;
use App\Traits\RatingAlreadyScanned;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use function NunoMaduro\Collision\Exceptions\getClassName;

class ScanDomainRatings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RatingAlreadyScanned;

    private $ratings = [
        DomainSimilarity::class,
        ContentRating::class,
        ImageRating::class,
        CssColor::class
    ];

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
        foreach ($this->ratings as $rating){

            if( $this->copyRating($this->spoofed_domain,$rating) ){
                return;
            }

            try {
                $domain = Domain::find($this->spoofed_domain->domain_id);
                $todomainsimilarity = $domain->domain_name . ", " . $this->spoofed_domain->spoofed_domain;
                $this->spoofed_domain->{$rating::dbColumnName()} = $rating::rate($todomainsimilarity);

            }catch (\Exception $exception){
                Log::error("Obtaining ".$rating::dbColumnName()." ratings for ".$this->spoofed_domain->spoofed_domain." failed: ".$exception->getMessage());
                $this->spoofed_domain->{$rating::dbColumnName()} = "failed";
            }

            $this->spoofed_domain->save();
        }
    }
}
