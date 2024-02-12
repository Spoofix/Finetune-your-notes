<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Domain;
use App\Jobs\WhoIsRating;
use App\Services\DnsTwist;
use App\Services\OpenSquat;
use App\Models\SpoofedDomain;
use App\Jobs\ScanDomainRatings;
use App\Services\SearchEngines;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Jobs\LocationSslAndRedirectsJob;

class SearchSimilarDomain extends Command
{

    private $domains_searchers = [
        OpenSquat::class,
        DnsTwist::class,
        SearchEngines::class
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
        if (!$last_batch) { // ensure last_batch is provided
            echo "Last Batch is required";
            return;
        }

        $domain_id = $this->option('domain_id');
        if (!$domain_id) { // ensure domain is provided
            echo "Domain id is required";
            return;
        }

        $domain = Domain::find($domain_id);
        if (!$domain) {
            echo "Domain id is not found";
            return;
        }

        $domain_ = trim($domain->domain_name);

        if ($this->triggerIfScannedToday($domain)) {
            Log::alert("Hpa {$domain_}");
            return;
        }

        foreach ($this->domains_searchers as $domains_searcher) {

            try {
                $returned_domains = $domains_searcher::search($domain_, $last_batch);
                Log::info(json_encode($returned_domains));
                $domains = array_merge($domains, $returned_domains);
            } catch (\Exception $exception) {
                Log::error($domains_searcher . " >> searching domain: " . $domain_ . ", failedi => " . $exception->getMessage() . ' <> ' . $exception->getTraceAsString());
            }
        }

        $unique_domains = array_values(array_unique($domains));

        foreach ($unique_domains as $unique_domain) {
            $created = SpoofedDomain::create([
                'domain_id' => $domain->id,
                'spoofed_domain' => $unique_domain,
                'csscolor' => 'processing',
                'domain_rating' => 'processing',
                'domainsimilarityrate' => 'processing',
                'htmls' => 'processing',
                'phashes' => 'processing',
                'last_batch' => $last_batch,
                'current_scan_status' => 'not_scanned'
            ]);

            WhoIsRating::dispatch($created);
            ScanDomainRatings::dispatch($created);
            LocationSslAndRedirectsJob::dispatch($created);
            //ssl etc hear
        }
    }

    private function triggerIfScannedToday($domain)
    {
        $returns = SpoofedDomain::where('domain_id', $domain->id)
            ->whereDate('created_at', Carbon::today()->toDateString())
            ->get();

        if (count($returns) == 0) {
            Log::alert("This here");
            return false;
        }

        foreach ($returns as $return) {
            // WhoIsRating::dispatch($return);
            // ScanDomainRatings::dispatch($return);
            WhoIsRating::dispatch($return);
            ScanDomainRatings::dispatch($return);
            LocationSslAndRedirectsJob::dispatch($return);
        }

        Log::alert("This huku: " . count($returns));

        return true;
    }
}
