<?php

namespace App\Http\Controllers\Backend\Receipt;

use App\Http\Controllers\Controller;
use App\Models\User;
use REWEParser\Parser;
use App\Models\ReweShop;
use App\Models\ReweBon;
use App\Models\ReweProduct;
use App\Models\ReweBonPosition;
use App\Http\Controllers\TelegramController;
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
        $bon = ReweBon::updateOrCreate(
            [
                "shop_id"       => $receipt->getShopNr(),
                "timestamp_bon" => $receipt->getTimestamp(),
                "bon_nr"        => $receipt->getBonNr()
            ],
            [
                "user_id"               => $user->id,
                "cashier_nr"            => $receipt->getCashierNr(),
                "cashregister_nr"       => $receipt->getCashregisterNr(),
                "paymentmethod"         => $receipt->getPaymentMethods()[0], //TODO: Support multiple payment methods
                "payed_cashless"        => $receipt->hasPayedCashless(),
                "payed_contactless"     => $receipt->hasPayedContactless(),
                "total"                 => $receipt->getTotal(),
                "earned_payback_points" => $receipt->getEarnedPaybackPoints(),
                "receipt_pdf"           => file_get_contents($file->path())
            ]
        );

        $positions = $receipt->getPositions();

        foreach($positions as $position) {
            $product = ReweProduct::firstOrCreate(["name" => $position->getName()]);

            ReweBonPosition::updateOrCreate(
                [
                    "bon_id"     => $bon->id,
                    "product_id" => $product->id
                ],
                [
                    "amount"       => $position->getAmount(),
                    "weight"       => $position->getWeight(),
                    "single_price" => $position->getPriceSingle()
                ]
            );
        }

        if($bon->wasRecentlyCreated && $bon->user !== null) {
            self::notifyUser($bon);
        }

        return $bon;
    }

    private static function notifyUser(ReweBon $receipt): void {
        if($receipt->user === null) {
            return;
        }

        $message = "<b>" . __(key: 'new-receipt-saved', locale: $receipt->user->locale) . "</b>" . "\r\n";
        $message .= __(key: 'store', locale: $receipt->user->locale) . ": REWE" . "\r\n";
        $message .= __(
            key:     'new-receipt-saved.text',
            replace: [
                         'count'  => $receipt->positions->count(),
                         'amount' => $receipt->total,
                     ],
            locale:  $receipt->user->locale
        );
        $message .= "\r\n";
        $message .= __(key: 'cashback', locale: $receipt->user->locale) . ': ' . $receipt->cashback_rate . " %" . "\r\n";

        $message .= "<i>" . $receipt->timestamp_bon->format("d.m.Y H:i") . "</i>" . "\r\n";
        $message .= "============================" . "\r\n";
        foreach($receipt->positions as $position) {
            if($position->weight !== null) {
                $message .= $position->weight . "kg ";
            } else {
                $message .= $position->amount . "x ";
            }
            $message .= $position->product->name . " (" . number_format($position->single_price, 2, ',', '.') . " €)" . "\r\n";
        }
        $message .= "============================" . "\r\n";
        $message .= "<a href='" . config('app.url') . "/rewe/receipt/" . $receipt->id . "'>" . __(key: 'receipt.show', locale: $receipt->user->locale) . "</a>";

        TelegramController::sendMessage($receipt->user, $message);
    }
}
