<?php

namespace App\Http\Controllers\Backend\Receipt\Grocy;

use App\Http\Controllers\Controller;
use App\Models\ReweBon;
use App\Http\Controllers\Backend\Receipt\Grocy\ApiController as GrocyBackend;
use App\Exceptions\NotConnectedException;
use Illuminate\Support\Collection;

abstract class ReceiptController extends Controller {

    /**
     * @param ReweBon $receipt
     *
     * @return Collection
     * @throws NotConnectedException
     */
    public static function addReceiptToStock(ReweBon $receipt): Collection {
        $user    = $receipt->user;
        $stocked = collect();
        foreach($receipt->positions->whereNull('grocy_transaction_id') as $position) {
            $grocyStockObject = GrocyBackend::addToStockByBarcode(
                user: $user,
                amount: $position->amount ?? $position->weight,
                price: $position->single_price,
                barcode: $position->product->name,
            );
            if(isset($grocyStockObject->transaction_id)) {
                $position->update(['grocy_transaction_id' => $grocyStockObject->transaction_id]);
                $stocked->push($position);
            }
        }
        return $stocked;
    }

}
