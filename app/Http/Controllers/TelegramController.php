<?php

namespace App\Http\Controllers;

use App\Exceptions\TelegramException;
use App\User;
use App\UserSettings;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{

    /**
     * @param User   $user
     * @param String $message
     * @return bool
     * @deprecated Will be removed later
     */
    public static function sendMessage(User $user, string $message)
    {
        $telegramID = UserSettings::get($user->id, 'telegram_id');

        if ($telegramID == null)
            return false;

        try {
            $telegramResponse = Telegram::sendMessage([
                                                          'chat_id'    => $telegramID,
                                                          'text'       => $message,
                                                          'parse_mode' => 'HTML'
                                                      ]);
            Log::debug($telegramResponse);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}
