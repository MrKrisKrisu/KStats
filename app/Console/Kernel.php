<?php

namespace App\Console;

use App\Console\Commands\Telegram_SetWebhook;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    protected $commands = [
        Telegram_SetWebhook::class
    ];

    protected function schedule(Schedule $schedule): void {
        $schedule->command('kstats:cleanup')
                 ->everySixHours();

        //Spotify
        $schedule->command('spotify:tokenRefresh')
                 ->everyFifteenMinutes()
                 ->evenInMaintenanceMode();

        $schedule->command('spotify:catchNowPlaying')
                 ->everyMinute()
                 ->runInBackground();

        $schedule->command('spotify:getTrackInfo')
                 ->everyFifteenMinutes();

        $schedule->command('spotify:playlistRefresh')->daily();
        $schedule->command('spotify:fetchGenres')->daily();

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
                 ->everyFifteenMinutes();

        $schedule->command('telegram:set_webhook')->daily();
    }

    protected function commands(): void {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
