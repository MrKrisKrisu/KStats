<?php

namespace App\Console\Commands;

use App\Http\Controllers\Backend\Spotify\SpotifySocialExploreController;
use App\Models\UserSettings;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

class SpotifySendExplore extends Command {

    protected $signature = 'spotify:sendExplore';

    public function handle(): int {
        $users = UserSettings::with(['user'])
                             ->where('name', 'tg_explore_time')
                             ->where('val', Carbon::now()->format('H:i'))
                             ->get()
                             ->map(function($setting) {
                                 return $setting->user;
                             });

        foreach($users as $user) {
            echo $user->username . PHP_EOL;
            try {
                SpotifySocialExploreController::sendExploreSuggestion($user);
            } catch(Exception $exception) {
                report($exception);
            }
        }
        return 0;
    }

}
