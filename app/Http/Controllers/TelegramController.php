<?php

namespace App\Http\Controllers;

use App\User;
use App\UserSettings;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    /**
     * @param User $user
     * @param String $message
     * @return bool
     */
    public static function sendMessage(User $user, String $message)
    {
        return true; //for development with real data...
        $telegramID = UserSettings::get($user->id, 'telegram_id');

        if ($telegramID == NULL)
            return false;

        $client = new Client();
        $result = $client->post('https://api.telegram.org/bot' . env('TELEGRAM_TOKEN') . '/sendMessage', [
            'headers' => [
                'Content-type' => 'application/json'
            ],
            'body' => json_encode([
                'chat_id' => $telegramID,
                'text' => $message,
                'parse_mode' => 'HTML'
            ])
        ]);

        if ($result->getStatusCode() != 200)
            return false;

        $data = json_decode($result->getBody()->getContents());

        if (isset($data->ok) && $data->ok)
            return true;
        throw new Expception("There was an error while sending message.");
    }

}
