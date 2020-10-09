<?php

namespace App\Console\Commands;

use App\Http\Controllers\TelegramController;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class Telegram_SetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set_webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Telegram Webhook';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $res = Telegram::setWebhook([
                                        'url' => env('APP_URL') . '/' . env('TELEGRAM_TOKEN') . '/webhook'
                                    ]);

        dump($res);

        return 0;
    }
}
