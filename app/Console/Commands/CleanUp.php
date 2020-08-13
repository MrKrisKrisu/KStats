<?php

namespace App\Console\Commands;

use App\Http\Controllers\ReweBonParser;
use App\Http\Controllers\ReweMailController;
use App\Http\Controllers\TelegramController;
use App\ReweBon;
use App\ReweBonPosition;
use App\ReweProduct;
use App\ReweShop;
use App\TwitterApiRequest;
use App\User;
use App\UserEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\PdfToText\Pdf;

class CleanUp extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "kstats:cleanup";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = " ";

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
        TwitterApiRequest::where('created_at', '<', Carbon::now()->addMinutes(-30))->delete();
    }

}
