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
        $auto_generated_queues = DB::table("jobs")/*(->where("queue","!=","default")*/->where('attempts',0)->pluck("queue")->toArray();
        $auto_generated_queues = array_unique(array_unique($auto_generated_queues));
        foreach ($auto_generated_queues as $auto_generated_queue){
            if(DB::table("jobs")->where('queue',$auto_generated_queue)->where('attempts',1)->exists()){
                Log::error("Job already running, skip");
                continue;
            }
            $process = new Process(["php","artisan","queue:work","--queue=".$auto_generated_queue,"--timeout=3000"]);
            if ($process->isRunning()){
                Log::error("The process is running, leave is alone!");
                continue;
            }
            $process->start();
            Log::info("Processing Job: ".$auto_generated_queue);
        }
    }
}
