<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\CallbackEvent;
use App\Jobs\ArtisanCommandJob;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $this->job($schedule, 'app:publish-albums', 'default')->everyMinute();
    }

    private function job(Schedule $schedule, string $commandName, string $queue): CallbackEvent
    {
        return $schedule->job(new ArtisanCommandJob($commandName), $queue);
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
