<?php

namespace App\Http\Controllers\Backend\Receipt;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserEmail;
use REWEParser\Parser;
use App\Models\ReweShop;
use App\Models\ReweBon;
use App\Models\ReweProduct;
use App\Models\ReweBonPosition;
use App\Http\Controllers\TelegramController;
use App\Exceptions\TelegramException;
use REWEParser\Exception\ReceiptParseException;
use Spatie\PdfToText\Exceptions\PdfNotFound;
use Illuminate\Http\UploadedFile;

abstract class ImportController extends Controller {

    public static function parseReweReceipt(User $user, UploadedFile $file): ?ReweBon {

        $receipt = Parser::parseFromPDF($file->path(), env('PDFTOTEXT_PATH', '/usr/bin/pdftotext'));

        if($receipt->getBonNr() === null || $receipt->getTimestamp() === null || $receipt->getShopNr() === null) {
            dump("Error while parsing eBon. Some important data can't be retrieved.");
            return null;
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
                                           "user_id"               => $user->id,
                                           "cashier_nr"            => $receipt->getCashierNr(),
                                           "cashregister_nr"       => $receipt->getCashregisterNr(),
                                           "paymentmethod"         => $receipt->getPaymentMethods()[0], //TODO: Support multiple payment methods
                                           "payed_cashless"        => $receipt->hasPayedCashless(),
                                           "payed_contactless"     => $receipt->hasPayedContactless(),
                                           "total"                 => $receipt->getTotal(),
                                           "earned_payback_points" => $receipt->getEarnedPaybackPoints(),
                                           "receipt_pdf"           => file_get_contents($file->path())
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

        if($bon->wasRecentlyCreated == 1 && $user->id != null) {
            try {
                $message = "<b>Neuer REWE Einkauf registriert</b>\r\n";
                $message .= count($positions) . " Produkte für " . number_format($bon->total, 2, ",", ".") . " €\r\n";
                $message .= "Erhaltenes Cashback: " . $bon->cashback_rate . "% \r\n";
                $message .= "<i>" . $bon->timestamp_bon->format("d.m.Y H:i") . "</i> \r\n";
                $message .= "============================ \r\n";
                foreach($positions as $position)
                    $message .= ($position->getWeight() !== null ? $position->getWeight() . "kg" : $position->getAmount() . "x") . " " . $position->getName() . " <i>" . number_format($position->getPriceTotal(), 2, ',', '.') . "€</i> \r\n";
                $message .= "============================ \r\n";
                $message .= "<a href='https://kstats.k118.de/rewe/receipt/" . $bon->id . "'>Bon anzeigen</a>";

                TelegramController::sendMessage($user, $message);
            } catch(TelegramException $e) {
                report($e);
                dump("Error while sending Telegram message");
            }
        }

        return $bon;
    }

}
