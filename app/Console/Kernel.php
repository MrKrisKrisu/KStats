<?php

namespace App\Console;

use App\Console\Commands\Telegram_SetWebhook;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Telegram_SetWebhook::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        $schedule->command('kstats:cleanup')
                 ->everySixHours();

        //Spotify
        $schedule->command('spotify:tokenRefresh')
                 ->everyFifteenMinutes()
                 ->evenInMaintenanceMode();

        $schedule->command('spotify:catchNowPlaying')
                 ->everyMinute()
                 ->evenInMaintenanceMode()
                 ->runInBackground();

        $schedule->command('spotify:getTrackInfo')
                 ->everyFifteenMinutes();

        $schedule->command('spotify:playlistRefresh')
                 ->daily();

        //REWE eBon Analyzer
        $schedule->command('rewe:parse')
                 ->everyFiveMinutes()
                 ->runInBackground();

        //Twitter
        $schedule->command('twitter:crawl_followers')
                 ->everyMinute()
                 ->runInBackground()
                 ->withoutOverlapping();
        $schedule->command('twitter:check_unfollows')
                 ->everyMinute()
                 ->runInBackground()
                 ->withoutOverlapping();

        $schedule->command('telegram:set_webhook')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
