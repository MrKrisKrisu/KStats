<?php

namespace App\Http\Controllers\Backend\Receipt\Grocy;

use App\Http\Controllers\Controller;
use App\Models\ReweBon;
use App\Http\Controllers\Backend\Receipt\Grocy\ApiController as GrocyBackend;
use App\Exceptions\NotConnectedException;
use Illuminate\Support\Collection;
use App\Exceptions\Grocy\ProductNotAssignableException;
use JsonException;
use App\Http\Controllers\TelegramController;

abstract class ReceiptController extends Controller {

    /**
     * @param ReweBon $receipt
     *
     * @return Collection
     * @throws NotConnectedException
     */
    public static function addReceiptToStock(ReweBon $receipt): Collection {
        $stocked = collect();
        foreach($receipt->positions->whereNull('grocy_transaction_id') as $position) {
            try {
                $grocyStockObject = GrocyBackend::addToStockByBarcode(
                    user:    $receipt->user,
                    amount:  $position->amount ?? $position->weight,
                    price:   $position->single_price,
                    barcode: $position->product->name,
                );
                if(isset($grocyStockObject->transaction_id)) {
                    $position->update(['grocy_transaction_id' => $grocyStockObject?->transaction_id]);
                    $stocked->push($position);
                }
            } catch(ProductNotAssignableException|NotConnectedException|JsonException) {
                continue;
            }
        }
        self::notifyUser($receipt, $stocked);
        return $stocked;
    }

    private static function notifyUser(ReweBon $receipt, Collection $stockedPositions): void {
        if(!isset($receipt->user->socialProfile->grocy_host) || $stockedPositions->count() === 0) {
            return;
        }

        $message = "<b>" . __(key: 'grocy.saved', locale: $receipt->user->locale) . "</b>" . "\r\n";
        $message .= __(key: 'grocy.saved.text', locale: $receipt->user->locale) . "\r\n";
        $message .= "============================" . "\r\n";
        foreach($stockedPositions as $position) {
            if($position->weight !== null) {
                $message .= $position->weight . "kg ";
            } else {
                $message .= $position->amount . "x ";
            }
            $message .= $position->product->name . "\r\n";
        }
        $message .= "============================" . "\r\n";

        TelegramController::sendMessage($receipt->user, $message);
    }
}
