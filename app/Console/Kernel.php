<?php

namespace App\Console;

use Carbon\Carbon;
use App\Models\Domain;
use App\Jobs\ScanDomains;
use App\Models\SpoofedDomain;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        Commands\ScanSpoofingDomains::class,
        Commands\ExpireOtpCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command("app:expire-otp")->everyTwoMinutes();
        //        $schedule->command('queue:work')->everyMinute();
        //        $schedule->command("domain:search")->daily();
        $schedule->call(function () {
            $Domains = Domain::all();
            foreach ($Domains as $Domain) {
                $latest = SpoofedDomain::where('domain_id', $Domain->id)->orderBy('id', 'desc')->first()->created_at;
                if ($latest && $latest->diffInHours(Carbon::now()) > 72) {
                    ScanDomains::dispatch([
                        'domain_id' => $Domain->id
                    ]);
                }
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
    // app/Console/Kernel.php
}
