<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

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

        if($telegramID === null) {
            return false;
        }

        try {
            $telegramResponse = Telegram::sendMessage([
                                                          'chat_id'    => $telegramID,
                                                          'text'       => $message,
                                                          'parse_mode' => 'HTML'
                                                      ]);
            Log::debug($telegramResponse);
            return true;
        } catch(Exception) {
            return false;
        }
    }

    public function handleTelegram(): ?string {
        $updates = Telegram::commandsHandler(true);
        Log::debug(json_decode($updates));
        return 'ok';
    }

}
