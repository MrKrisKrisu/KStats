<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class Telegram_SetWebhook extends Command {

    protected $signature = 'telegram:set_webhook';
    protected $description = 'Set Telegram Webhook';

    public function handle(): int {
        $url = strtr(':url/:token/webhook', [
            ':url'   => config('app.url'),
            ':token' => config('telegram.bots.mybot.token')
        ]);

        echo "Set weebhook to $url. \r\n";

        $res = Telegram::setWebhook([
                                        'url' => $url
                                    ]);

        dump($res);

        return 0;
    }
}
