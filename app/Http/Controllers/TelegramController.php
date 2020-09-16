<?php

namespace App\Http\Controllers;

use App\Exceptions\TelegramException;
use App\User;
use App\UserSettings;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TelegramController extends Controller
{
    /**
     * Required once to create the Webhook at Telegram API to handle Messages sent to the bot
     * @return boolean
     * @throws GuzzleException
     */
    public static function setWebhook()
    {
        $client = new Client();
        $result = $client->post('https://api.telegram.org/bot' . env('TELEGRAM_TOKEN') . '/setWebhook', [
            'headers' => [
                'Content-type' => 'application/json'
            ],
            'body' => json_encode([
                'url' => env('APP_URL') . '/api/telegram/webhook'
            ])
        ]);

        if ($result->getStatusCode() != 200)
            return false;

        $data = json_decode($result->getBody()->getContents());

        if ($data->ok)
            return true;
        return false;
    }

    /**
     * @param User $user
     * @param String $message
     * @return bool
     * @throws TelegramException
     */
    public static function sendMessage(User $user, string $message)
    {
        $telegramID = UserSettings::get($user->id, 'telegramID');

        if ($telegramID == NULL)
            return false;

        return self::sendMessageToChat($telegramID, $message);
    }

    /**
     * @param int $telegramID
     * @param String $message
     * @return bool
     * @throws TelegramException
     */
    private static function sendMessageToChat(int $telegramID, string $message)
    {
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
            throw new TelegramException("StatusCode != 200: " . $result->getBody()->getContents());

        $data = json_decode($result->getBody()->getContents());

        if (isset($data->ok) && $data->ok)
            return true;
        throw new TelegramException("There was an error while sending message.");
    }

    /**
     * Handles incoming Telegram Webhook (ex. new messages)
     * Thrown by API Route
     * @throws TelegramException
     */
    public static function handleWebhook()
    {
        $content = file_get_contents("php://input");
        $update = json_decode($content, true);

        if (!isset($update['message']['chat']['id']))
            return;

        $chatID = $update['message']['chat']['id'];
        $messageText = $update['message']['text'];
        $commandEx = explode(' ', explode('@', $messageText)[0]);

        if ($commandEx[0] == '/start') {
            self::sendMessageToChat($chatID, "<b>Willkommen bei KStats!</b>\r\n"
                . "Um Telegram mit KStats nutzen zu können musst du deinen Account mit Telegram verbinden.\r\n"
                . "Auf der Seite 'Einstellungen' kannst du deinen Telegram-ConnectCode generieren. Bitte gebe den Befehl <i>/connect CODE</i> ein und schicke diesen ab. (Ersetze <b>CODE</b> mit deinem Code.)");
            return;
        }

        if ($commandEx[0] == '/connect') {
            if (!isset($commandEx[1])) {
                self::sendMessageToChat($chatID, "Bitte gebe noch deinen Code als zweites Argument an.\r\n\r\n-> /connect CODE");
                return;
            }

            $us = UserSettings::where('name', 'telegram_connectCode')
                ->where('val', $commandEx[1])
                ->where('updated_at', '>', Carbon::now()->addHours('-1'))
                ->first();
            if ($us == NULL) {
                self::sendMessageToChat($chatID, "Der ConnectCode ist nicht korrekt.");
                return;
            }

            self::sendMessageToChat($chatID, "Hallo " . $us->user->username . '! Dein Account ist jetzt erfolgreich verknüpft.');

            UserSettings::set($us->user->id, 'telegramID', $chatID);
            UserSettings::set($us->user->id, 'telegram_connectCode', '');
            return;

        }

        self::sendMessageToChat($chatID, "Der Befehl existiert nicht.");
    }

}
