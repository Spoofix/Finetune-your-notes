<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ProcessAllQueues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-all-queues';

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
        Log::info("Started Jobs");
        $auto_generated_queues = DB::table("jobs")/*(->where("queue","!=","default")*/->pluck("queue")->toArray();
        foreach ($auto_generated_queues as $auto_generated_queue){
            $process = new Process(["php","artisan","queue:work","--queue=".$auto_generated_queue,"--timeout=3000"]);
            $process->start();
            Log::info("Processing Job: ".$auto_generated_queue);
        }
    }
}
