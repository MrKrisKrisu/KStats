<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Exception;
use App\Models\SpotifyTrackRating;
use App\Models\SpotifyTrack;
use App\Models\SocialLoginProfile;

class TelegramController extends Controller {

    /**
     * @param User   $user
     * @param String $message
     *
     * @return bool
     * @deprecated Will be removed later
     */
    public static function sendMessage(User $user, string $message) {
        $telegramID = $user->socialProfile->telegram_id;

        if($telegramID == null)
            return false;

        try {
            $telegramResponse = Telegram::sendMessage([
                                                          'chat_id'    => $telegramID,
                                                          'text'       => $message,
                                                          'parse_mode' => 'HTML'
                                                      ]);
            Log::debug($telegramResponse);
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    public function handleTelegram() {
        $updates = Telegram::commandsHandler(true);

        try {
            $rawCallback = $updates['callback_query']['data'];
            $chatId      = $updates['callback_query']['from']['id'];
            $callback    = json_decode(base64_decode($rawCallback));
            if(!isset($callback?->track_id) || !isset($callback?->like)) {
                Log::debug('no like!!!');
                return;
            }
            $like = (int)$callback->like;
            if($like < -1 || $like > 1) {
                Log::debug('like wrong!!!' . $like);
                return;
            }
            $user = SocialLoginProfile::where('telegram_id', $chatId)->first()?->user;

            $track = SpotifyTrack::where('track_id', $callback->track_id)->first();

            if($track == null) {
                Log::debug('track wrong!!!' . $like);
                return;
            }

            SpotifyTrackRating::updateOrCreate([
                                                   'user_id'  => $user->id,
                                                   'track_id' => $track->id,
                                               ], [
                                                   'rating' => $like,
                                               ]);

            TelegramController::sendMessage($user, "Dein Feedback wurde gespeichert und wird helfen, dass deine Freundschaftsplaylisten besser werden! 🥰");
            return;
        } catch(Exception) {

        }

        //Log::debug($updates['callback_query']['data']);
        //Log::debug($updates['callback_query']['from']['id']);
        Log::debug();


        Log::debug(json_decode($updates));
        return 'ok';
    }

}
