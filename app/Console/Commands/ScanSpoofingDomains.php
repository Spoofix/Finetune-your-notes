<?php

namespace App\Console\Commands;

use App\Models\Domain;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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
        $user_id = $this->option('user_id');
        $domain_id = $this->option('domain_id');

        $domain = Domain::query();

        if (!empty($user_id)) {
            $domain = $domain->where('user_id', $user_id);
        }

        if (!empty($domain_id)) {
            $domain = $domain->where('id', $domain_id);
        }

        //new for testing
        // $domain = $domain->get();

        $domain->chunkById(5, function ($domains) { // process 5 at a time
            $last_batch = time(); // Will explain its purpose
            foreach ($domains as $domain) {
                $domain->status = "PROCESSING";
                $domain->save();

                info("-- CALL SearchSimilarDomain STARTED --");
                Artisan::call(SearchSimilarDomain::class, ['--domain_id' => $domain->id, '--last_batch' => $last_batch]);
                info("-- CALL SearchSimilarDomain ENDED --");

                $domain->status = "SCANNED";
                $domain->save();
            }
        });
    }
}
