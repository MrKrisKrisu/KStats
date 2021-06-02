<?php

namespace App\Console\Commands;

use App\Exceptions\TelegramException;
use App\Http\Controllers\ReweBonParser;
use App\Http\Controllers\ReweMailController;
use App\Http\Controllers\TelegramController;
use App\Models\ReweBon;
use App\Models\ReweBonPosition;
use App\Models\ReweProduct;
use App\Models\ReweShop;
use App\Models\User;
use App\Models\UserEmail;
use Illuminate\Console\Command;
use REWEParser\Exception\ReceiptParseException;
use REWEParser\Parser;
use Spatie\PdfToText\Exceptions\PdfNotFound;

class REWE_ParseBon extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "rewe:parse {days=2}";

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
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $files = ReweMailController::fetchMailAttachments($this->argument("days"));

        foreach($files as $bonAttachment) {
            try {
                $userEmail = UserEmail::firstOrCreate(["email" => $bonAttachment->getEMail()]);

                $filename = $bonAttachment->getFilename();

                $receipt = Parser::parseFromPDF($bonAttachment->getFilename(), env('PDFTOTEXT_PATH', '/usr/bin/pdftotext'));

                if($receipt->getBonNr() === null || $receipt->getTimestamp() === null || $receipt->getShopNr() === null) {
                    dump("Error while parsing eBon. Some important data can't be retrieved.");
                    return;
                }

                $shop = $receipt->getShop();

                ReweShop::updateOrCreate(
                    [
                        "id" => $receipt->getShopNr()
                    ],
                    [
                        "name"    => $shop->getName(),
                        "address" => $shop->getAddress(),
                        "zip"     => $shop->getPostalCode(),
                        "city"    => $shop->getCity(),
                    ]
                );
                $bon = ReweBon::updateOrCreate([
                                                   "shop_id"       => $receipt->getShopNr(),
                                                   "timestamp_bon" => $receipt->getTimestamp(),
                                                   "bon_nr"        => $receipt->getBonNr()
                                               ], [
                                                   "user_id"               => $userEmail->verified_user_id,
                                                   "cashier_nr"            => $receipt->getCashierNr(),
                                                   "cashregister_nr"       => $receipt->getCashregisterNr(),
                                                   "paymentmethod"         => $receipt->getPaymentMethods()[0], //TODO: Support multiple payment methods
                                                   "payed_cashless"        => $receipt->hasPayedCashless(),
                                                   "payed_contactless"     => $receipt->hasPayedContactless(),
                                                   "total"                 => $receipt->getTotal(),
                                                   "earned_payback_points" => $receipt->getEarnedPaybackPoints(),
                                                   "receipt_pdf"           => file_get_contents($filename)
                                               ]);

                $positions = $receipt->getPositions();

                foreach($positions as $position) {
                    $product = ReweProduct::firstOrCreate(["name" => $position->getName()]);

                    ReweBonPosition::updateOrCreate([
                                                        "bon_id"     => $bon->id,
                                                        "product_id" => $product->id
                                                    ], [
                                                        "amount"       => $position->getAmount(),
                                                        "weight"       => $position->getWeight(),
                                                        "single_price" => $position->getPriceSingle()
                                                    ]);
                }

                if($bon->wasRecentlyCreated == 1 && $userEmail->verified_user_id != null) {
                    try {
                        $message = "<b>Neuer REWE Einkauf registriert</b>\r\n";
                        $message .= count($positions) . " Produkte für " . number_format($bon->total, 2, ",", ".") . " €\r\n";
                        $message .= "Erhaltenes Cashback: " . $bon->cashback_rate . "% \r\n";
                        $message .= "<i>" . $bon->timestamp_bon->format("d.m.Y H:i") . "</i> \r\n";
                        $message .= "============================ \r\n";
                        foreach($positions as $position)
                            $message .= ($position->getWeight() !== null ? $position->getWeight() . "kg" : $position->getAmount() . "x") . " " . $position->getName() . " <i>" . number_format($position->getPriceTotal(), 2, ',', '.') . "€</i> \r\n";
                        $message .= "============================ \r\n";
                        $message .= "<a href='https://k118.de/rewe/receipt/" . $bon->id . "'>Bon anzeigen</a>";

                        TelegramController::sendMessage(User::find($userEmail->verified_user_id), $message);
                    } catch(TelegramException $e) {
                        report($e);
                        dump("Error while sending Telegram message");
                    }
                }
            } catch(ReceiptParseException $e) {
                report($e);
                dump("Error while parsing eBon. Is the format compatible?");
            } catch(PdfNotFound $e) {
                report($e);
            }
        }

        return 0;
    }

}
