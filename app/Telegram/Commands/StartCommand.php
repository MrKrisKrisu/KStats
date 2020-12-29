<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command {

    protected $name        = "start";
    protected $description = "Start Command to get you started";

    public function handle(): int {
        $this->replyWithMessage([
                                    'text' => "<b>Willkommen bei KStats!</b>\r\n"
                                              . "Um Telegram mit KStats nutzen zu können musst du deinen Account mit Telegram verbinden.\r\n"
                                              . "Auf der Seite 'Einstellungen' kannst du deinen Telegram-ConnectCode generieren. Bitte gebe den Befehl <i>/connect CODE</i> ein und schicke diesen ab. (Ersetze <b>CODE</b> mit deinem Code.)"
                                ]);
        return 0;
    }
}