<?php

namespace App\Console\Commands;

use App\Jobs\ScanDomainRatings;
use App\Models\Domain;
use App\Models\SpoofedDomain;
use App\Services\DnsTwist;
use App\Services\OpenSquat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SearchSimilarDomain extends Command
{

    private $domains_searchers = [
        OpenSquat::class,
        DnsTwist::class
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:search {--domain_id=} {--last_batch=}';

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
        $domains = [];
        $last_batch = $this->option('last_batch');
        if(!$last_batch){ // ensure last_batch is provided
            echo "Last Batch is required";
            return;
        }

        $domain_id = $this->option('domain_id');
        if(!$domain_id){ // ensure domain is provided
            echo "Domain id is required";
            return;
        }

        $domain = Domain::find($domain_id);
        if(!$domain){
            echo "Domain id is not found";
            return;
        }

        $domain_ = trim($domain->domain_name);
        $domain__ = explode(".",$domain_);
        if( !empty($domain__[0]) ) {
            $domain_ = trim($domain__[0]);
        }

        foreach ($this->domains_searchers as $domains_searcher){
            try {
                array_merge($domains,$domains_searcher::search($domain_));
            }catch (\Exception $exception){
                Log::error("searching domain: ".$domain_.", failed => ".$exception->getMessage());
            }
        }

        $unique_domains = array_values(array_unique($domains));
        foreach ($unique_domains as $unique_domain){
            $created = SpoofedDomain::create([
                'domain_id'=>$domain->id,
                'spoofed_domain'=>$unique_domain,
                'csscolor'=>'processing',
                'domainsimilarityrate'=>'processing',
                'htmls'=>'processing',
                'phashes'=>'processing',
                'last_batch' => $last_batch,
            ]);

            ScanDomainRatings::dispatch($created);
        }
    }
}
