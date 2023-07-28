<?php

namespace App\Jobs;

use App\Console\Commands\ScanSpoofingDomains;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ScanDomains implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    public $tries = 5;
    public $tag = NULL;
    /**
     * Create a new job instance.
     * @param $dt
     */
    public function __construct(array $dt)
    {
        $this->data = $dt;
        if(isset($this->data['domain_id'])) {
            $this->tag = "ScanDomains" . $this->data['domain_id'];
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $actions=[];

        if(!empty($this->data['user_id'])){
            $actions['--user_id'] = $this->data['user_id'];
        }

        if(!empty($this->data['domain_id'])){
            $actions['--domain_id'] = $this->data['domain_id'];
        }

        info("-- CALL STARTED --");
        Artisan::call(ScanSpoofingDomains::class,$actions);
        info("-- CALL ENDED --");
    }

    public function toArray()
    {
        return [
            'tag' => $this->tag,
            // Add other properties here if needed
        ];
    }
}
