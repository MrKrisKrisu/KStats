<?php

namespace App\Console;

use App\Console\Commands\TelegramSetWebhook;
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
        TelegramSetWebhook::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('spotify:tokenRefresh')->everyFifteenMinutes();
        $schedule->command('spotify:catchNowPlaying')->everyMinute();
        $schedule->command('spotify:getTrackInfo')->everyFifteenMinutes();
        $schedule->command('spotify:playlistRefresh')->daily();

        $schedule->command('rewe:parse')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
