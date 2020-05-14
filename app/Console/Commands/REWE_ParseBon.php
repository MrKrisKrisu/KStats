<?php

namespace App\Console\Commands;

use App\Http\Controllers\ReweBonParser;
use App\Http\Controllers\ReweMailController;
use App\Http\Controllers\TelegramController;
use App\ReweBon;
use App\ReweBonPosition;
use App\ReweProduct;
use App\ReweShop;
use App\User;
use App\UserEmail;
use Illuminate\Console\Command;
use Spatie\PdfToText\Pdf;

class REWE_ParseBon extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rewe:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' ';

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
        $files = ReweMailController::fetchMailAttachments();

        foreach ($files as $bonAttachment) {
            try {
                $userEmail = UserEmail::firstOrCreate(['email' => $bonAttachment->getEMail()]);
                $pdf = new Pdf(env('PDFTOTEXT_PATH'));
                $text = $pdf->setPdf($bonAttachment->getFilename())->text();

                $parser = new ReweBonParser($text);

                if ($parser->getBonNr() === NULL || $parser->getTimestamp() === NULL || $parser->getShopNr() === NULL) {
                    dump("Error while parsing eBon. Some important data can't be retrieved.");
                    return;
                }

                ReweShop::updateOrCreate(
                    [
                        'id' => $parser->getShopNr()
                    ],
                    [
                        'name' => 'coming soon'
                    ]
                );
                $bon = ReweBon::updateOrCreate([
                    'shop_id' => $parser->getShopNr(),
                    'timestamp_bon' => $parser->getTimestamp(),
                    'bon_nr' => $parser->getBonNr()
                ], [
                    'user_id' => $userEmail->verified_user_id,
                    'cashier_nr' => $parser->getCashierNr(),
                    'cashregister_nr' => $parser->getCashregisterNr(),
                    'paymentmethod' => $parser->getPaymentMethods()[0], //TODO: Support multiple payment methods
                    'payed_cashless' => 0, //TODO
                    'payed_contactless' => 0, //TODO,
                    'total' => $parser->getTotal(),
                    'earned_payback_points' => $parser->getEarnedPaybackPoints(),
                    'raw_bon' => $text
                ]);

                $positions = $parser->getPositions();

                foreach ($positions as $position) {
                    $product = ReweProduct::firstOrCreate(['name' => $position['name']]);

                    ReweBonPosition::updateOrCreate([
                        'bon_id' => $bon->id,
                        'product_id' => $product->id
                    ], [
                        'amount' => $position['amount'] ?? (!isset($position['weight']) ? 1 : NULL),
                        'weight' => $position['weight'] ?? NULL,
                        'single_price' => $position['price_single'] ?? $position['price_total']
                    ]);
                }

                if ($userEmail->verified_user_id != NULL) {
                    $message = "<b>Neuer REWE Einkauf registriert</b>\r\n";
                    $message .= count($positions) . " Produkte für " . $bon->total . " €\r\n";
                    $message .= "Erhaltenes Cashback: " . round($bon->earned_payback_points / ($bon->total * 100) * 100, 2) . '%';

                    TelegramController::sendMessage(User::find($userEmail->verified_user_id), $message);
                }
            } catch (\Exception $e) {
                dump($e);
                dump("Error while parsing eBon. Is the format compatible?");
            }
        }
    }

}
