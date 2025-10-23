<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\SendTestVerification::class,
        \App\Console\Commands\CreateAdminUsers::class,
        \App\Console\Commands\ExportSeeders::class,
        \App\Console\Commands\SetSuperAdmin::class,
        \App\Console\Commands\DeleteOldRejectedRecords::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run once daily to delete rejected records older than 7 days
        $schedule->command('scms:delete-old-rejected')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
