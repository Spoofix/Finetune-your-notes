<?php

namespace App\Jobs;

use App\Models\SpoofedDomain;
use App\Services\ContentRating;
use App\Services\CssColor;
use App\Services\DomainSimilarity;
use App\Services\ImageRating;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScanDomainRatings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ratings = [
        CssColor::class,
        ContentRating::class,
        ImageRating::class,
        DomainSimilarity::class
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
            try {
                $this->spoofed_domain->{$rating::dbColumnName()} = $rating->rate($this->spoofed_domain);
            }catch (\Exception $exception){
                Log::error("Obtaining css ratings for ".$this->spoofed_domain->spoofed_domain." failed: ".$exception->getMessage());
                $this->spoofed_domain->{$rating::dbColumnName()} = "failed";
            }

            $this->spoofed_domain->save();
        }
    }
}
