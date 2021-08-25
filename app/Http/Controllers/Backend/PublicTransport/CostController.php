<?php

namespace App\Http\Controllers\Backend\PublicTransport;

use App\Http\Controllers\Controller;
use App\Models\PublicTransportCard;

abstract class CostController extends Controller {

    public static function getEffectiveCosts(PublicTransportCard $card): float {
        $cost              = $card->cost;
        $saved             = $card->journeys->sum('saved');
        $complaintCashback = 0;
        return $cost - $saved - $complaintCashback;
    }
}
