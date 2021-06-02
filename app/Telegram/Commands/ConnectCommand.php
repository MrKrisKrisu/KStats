<?php

namespace App\Telegram\Commands;

use App\Models\UserSettings;
use Carbon\Carbon;
use Telegram\Bot\Commands\Command;

class ConnectCommand extends Command {

    protected $name        = "connect";
    protected $pattern     = "{code}";
    protected $description = "Connect your KStats Account with Telegram";

    public function handle() {

        if(!isset($this->getArguments()['code'])) {
            $this->replyWithMessage([
                                        'text' => 'Bitte gib den Befehl im Format "/connect {code}" ein.'
                                    ]);
            return;
        }

        if(!is_numeric($this->getArguments()['code'])) {
            $this->replyWithMessage([
                                        'text' => 'Fehler: Der Code darf nur Zahlen beinhalten.'
                                    ]);
            return;
        }

        if(strlen($this->getArguments()['code']) != 6) {
            $this->replyWithMessage([
                                        'text' => 'Fehler: Der Code muss 6 Zeichen lang sein.'
                                    ]);
            return;
        }

        $userSetting = UserSettings::where('name', 'telegram_connectCode')
                                   ->where('val', $this->getArguments()['code'])
                                   ->where('updated_at', '>', Carbon::now()->addHours('-1'))
                                   ->first();

        if($userSetting == null) {
            $this->replyWithMessage([
                                        'text' => 'Fehler: Der Code ist nicht korrekt..'
                                    ]);
            return;
        }


        $this->replyWithMessage([
                                    'text' => "Hallo " . $userSetting->user->username . '! Dein Account ist jetzt erfolgreich verknüpft.'
                                ]);

        $userSetting->user->socialProfile->update(['telegram_id' => $this->getUpdate()->getChat()->get('id')]);
        UserSettings::set($userSetting->user->id, 'telegram_connectCode', '');

    }
}