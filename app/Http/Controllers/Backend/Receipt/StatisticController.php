<?php

namespace App\Http\Controllers\Backend\Receipt;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Collection;
use REWEParser\Parser;
use App\Models\ReweShop;
use App\Models\ReweBon;
use App\Models\ReweProduct;
use App\Models\ReweBonPosition;
use App\Http\Controllers\TelegramController;
use Illuminate\Http\UploadedFile;

abstract class StatisticController extends Controller {

    public static function getReceiptsByHour(User $user): Collection {
        return $user->reweReceipts
            ->groupBy(function($item, $key) {
                return $item['timestamp_bon']->hour;
            })
            ->union([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23])
            ->sortKeys()
            ->map(function($row) {
                return $row instanceof Collection ? $row->count() : 0;
            });
    }

    public static function getTopBrands(User $user): Collection {
        return $user->reweReceipts
            ->groupBy(function(ReweBon $receipt) {
                return $receipt?->shop?->brand_id;
            })
            ->map(function(Collection $rows) {
                return [
                    'brand' => $rows->first()?->shop?->brand,
                    'sum'   => $rows->sum('total'),
                    'count' => $rows->count(),
                ];
            });
    }
}
