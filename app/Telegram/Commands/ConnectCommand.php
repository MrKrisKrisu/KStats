<?php

namespace App\Telegram\Commands;

use App\UserSettings;
use Carbon\Carbon;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class ConnectCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "connect";

    protected $pattern = "{code}";

    /**
     * @var string Command Description
     */
    protected $description = "Connect your KStats Account with Telegram";

    /**
     * @inheritdoc
     */
    public function handle()
    {

        if (!isset($this->getArguments()['code'])) {
            $this->replyWithMessage([
                                        'text' => 'Bitte gib den Befehl im Format "/connect {code}" ein.'
                                    ]);
            return;
        }

        if (!is_numeric($this->getArguments()['code'])) {
            $this->replyWithMessage([
                                        'text' => 'Fehler: Der Code darf nur Zahlen beinhalten.'
                                    ]);
            return;
        }

        if (strlen($this->getArguments()['code']) != 6) {
            $this->replyWithMessage([
                                        'text' => 'Fehler: Der Code muss 6 Zeichen lang sein.'
                                    ]);
            return;
        }

        $us = UserSettings::where('name', 'telegram_connectCode')
                          ->where('val', $this->getArguments()['code'])
                          ->where('updated_at', '>', Carbon::now()->addHours('-1'))
                          ->first();

        if ($us == null) {
            $this->replyWithMessage([
                                        'text' => 'Fehler: Der Code ist nicht korrekt..'
                                    ]);
            return;
        }


        $this->replyWithMessage([
                                    'text' => "Hallo " . $us->user->username . '! Dein Account ist jetzt erfolgreich verknüpft.'
                                ]);

        UserSettings::set($us->user->id, 'telegramID', $this->getUpdate()->getChat()->get('id'));
        UserSettings::set($us->user->id, 'telegram_connectCode', '');

    }
}