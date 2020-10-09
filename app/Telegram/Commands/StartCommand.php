<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "Start Command to get you started";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $this->replyWithMessage([
                                    'text' => "<b>Willkommen bei KStats!</b>\r\n"
                                        . "Um Telegram mit KStats nutzen zu können musst du deinen Account mit Telegram verbinden.\r\n"
                                        . "Auf der Seite 'Einstellungen' kannst du deinen Telegram-ConnectCode generieren. Bitte gebe den Befehl <i>/connect CODE</i> ein und schicke diesen ab. (Ersetze <b>CODE</b> mit deinem Code.)"
                                ]);
    }
}