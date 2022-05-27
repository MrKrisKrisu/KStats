<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Backend\Receipt\StatisticController;
use App\Models\ReweBon;
use App\Models\ReweBonPosition;
use App\Models\ReweProduct;
use App\Models\ReweShop;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ReweController extends Controller {

    public function index(): Renderable {
        auth()->user()->loadMissing(['reweReceipts', 'reweReceipts.shop']);

        $favouriteProducts = DB::table('rewe_bons')
                               ->join('rewe_bon_positions', 'rewe_bon_positions.bon_id', '=', 'rewe_bons.id')
                               ->join('rewe_products', 'rewe_products.id', '=', 'rewe_bon_positions.product_id')
                               ->where('rewe_bons.user_id', auth()->user()->id)
                               ->where('rewe_products.hide', 0)
                               ->groupBy(['rewe_products.id', 'rewe_products.name'])
                               ->select(['rewe_products.id', 'rewe_products.name', DB::raw('COUNT(*) as cnt')])
                               ->orderByDesc('cnt')
                               ->limit(50)
                               ->get();

        $products_vegetarian = DB::table('rewe_products')
                                 ->join('rewe_bon_positions', 'rewe_bon_positions.product_id', '=', 'rewe_products.id')
                                 ->join('rewe_bons', 'rewe_bon_positions.bon_id', '=', 'rewe_bons.id')
                                 ->join('rewe_crowdsourcing_vegetarian_view', 'rewe_crowdsourcing_vegetarian_view.product_id', '=', 'rewe_products.id')
                                 ->where('rewe_bons.user_id', auth()->user()->id)
                                 ->where('rewe_products.hide', 0)
                                 ->groupBy('rewe_crowdsourcing_vegetarian_view.vegetarian')
                                 ->select(['rewe_crowdsourcing_vegetarian_view.vegetarian', DB::raw('COUNT(*) AS cnt')])
                                 ->orderBy(DB::raw('COUNT(*)'), 'desc')
                                 ->get();

        $topByCategoryCount = DB::table('rewe_products')
                                ->where('rewe_bons.user_id', auth()->user()->id)
                                ->join('rewe_bon_positions', 'rewe_bon_positions.product_id', '=', 'rewe_products.id')
                                ->join('rewe_bons', 'rewe_bons.id', '=', 'rewe_bon_positions.bon_id')
                                ->join('rewe_product_categories_view', 'rewe_product_categories_view.product_id', '=', 'rewe_products.id')
                                ->join('rewe_product_categories', 'rewe_product_categories_view.category_id', '=', 'rewe_product_categories.id')
                                ->groupBy(['rewe_product_categories.id', 'rewe_product_categories.name'])
                                ->select([
                                             DB::raw('rewe_product_categories.id AS category_id'),
                                             DB::raw('rewe_product_categories.name AS category_name'),
                                             DB::raw('COUNT(*) AS cnt')
                                         ])
                                ->orderByDesc(DB::raw('COUNT(*)'))
                                ->get();


        $topByCategoryPrice = DB::table('rewe_products')
                                ->where('rewe_bons.user_id', auth()->user()->id)
                                ->join('rewe_bon_positions', 'rewe_bon_positions.product_id', '=', 'rewe_products.id')
                                ->join('rewe_bons', 'rewe_bons.id', '=', 'rewe_bon_positions.bon_id')
                                ->join('rewe_product_categories_view', 'rewe_product_categories_view.product_id', '=', 'rewe_products.id')
                                ->join('rewe_product_categories', 'rewe_product_categories_view.category_id', '=', 'rewe_product_categories.id')
                                ->groupBy(['rewe_product_categories.id', 'rewe_product_categories.name'])
                                ->select([
                                             DB::raw('rewe_product_categories.id AS category_id'),
                                             DB::raw('rewe_product_categories.name AS category_name'),
                                             DB::raw('SUM(rewe_bon_positions.single_price) AS price')
                                         ])
                                ->orderByDesc(DB::raw('SUM(rewe_bon_positions.single_price)'))
                                ->get();

        $monthlySpend = auth()->user()->reweReceipts->groupBy(function($receipt) {
            return $receipt->timestamp_bon->format('m.Y');
        })->map(function($receipts) {
            return $receipts->sum('total');
        });

        return view('rewe_ebon.overview', [
            'mostUsedPaymentMethod' => $this->getUsersMostUsedPaymentMethod(auth()->user()) ?? '¯\_(ツ)_/¯',
            'products_vegetarian'   => $products_vegetarian,
            'favouriteProducts'     => $favouriteProducts,
            'payment_methods'       => $this->getPaymentMethods(auth()->user()),
            'forecast'              => self::getForecast(),
            'topByCategoryCount'    => $topByCategoryCount,
            'topByCategoryPrice'    => $topByCategoryPrice,
            'ebonKey'               => UserSettings::get(auth()->user()->id, 'eBonKey', md5(rand(0, 99) . time())),
            'monthlySpend'          => $monthlySpend,
            'topMarkets'            => $this->getTopMarkets(auth()->user()),
            'receiptsByHour'        => StatisticController::getReceiptsByHour(auth()->user()),
            'topBrand'              => StatisticController::getTopBrands(auth()->user())->sortByDesc('sum')->first(),
        ]);
    }

    public function downloadRawReceipt(int $receipt_id) {
        $receipt = ReweBon::find($receipt_id);

        if($receipt == null || $receipt->user_id != auth()->user()->id)
            return response("No permission", 401);

        return response($receipt->receipt_pdf, 200)
            ->header('Content-Type', 'application/pdf');
    }

    public function renderBonDetails(int $receipt_id) {
        $bon = ReweBon::find($receipt_id);

        if($bon->user->id != auth()->user()->id)
            return Redirect::route('rewe')->withErrors(['msg', 'No Permissions to access this bon.']);

        return view('rewe_ebon.receipt_details', [
            'bon' => $bon
        ]);
    }

    public static function getMailKey() {
        $key = UserSettings::where('user_id', auth()->user()->id)->where('name', 'rewe_ebon_key')->first();
        if($key !== null)
            return $key->val;

        $key = md5(auth()->user()->id . time() . rand(1, 99));

        UserSettings::create([
                                 'user_id' => auth()->user()->id,
                                 'name'    => 'rewe_ebon_key',
                                 'val'     => $key
                             ]);

        return $key;
    }

    /**
     * @return Collection
     */
    public static function getForecast(): Collection {
        return DB::table('rewe_bons')
                 ->join('rewe_bon_positions', 'rewe_bons.id', '=', 'rewe_bon_positions.bon_id')
                 ->join('rewe_products', 'rewe_bon_positions.product_id', '=', 'rewe_products.id')
                 ->where('rewe_bons.user_id', auth()->user()->id)
                 ->groupBy(['rewe_products.id', 'rewe_products.name'])
                 ->havingRaw('cnt > 2 AND nextTS > (NOW() - INTERVAL 10 DAY)')
                 ->select([
                              'rewe_products.id',
                              'rewe_products.name',
                              DB::raw('MAX(rewe_bons.timestamp_bon) AS lastTS'),
                              DB::raw('MIN(rewe_bons.timestamp_bon) AS firstTS'),
                              DB::raw('COUNT(rewe_bons.timestamp_bon) AS cnt'),
                              DB::raw('TIMESTAMPDIFF(HOUR, MIN(rewe_bons.timestamp_bon), MAX(rewe_bons.timestamp_bon)) / COUNT(rewe_bons.timestamp_bon) AS avgHours'),
                              DB::raw('TIMESTAMPADD(HOUR, TIMESTAMPDIFF(HOUR, MIN(rewe_bons.timestamp_bon), MAX(rewe_bons.timestamp_bon)) / COUNT(rewe_bons.timestamp_bon), MAX(rewe_bons.timestamp_bon) ) AS nextTS')
                          ])
                 ->orderBy('nextTS')
                 ->get();
    }

    /**
     * @param User $user
     *
     * @return ?string
     */
    public function getUsersMostUsedPaymentMethod(User $user): ?string {
        return $user->reweReceipts
            ->groupBy('paymentmethod')
            ->map(function($methods) {
                return $methods->count();
            })
            ->sortByDesc(function($count) {
                return $count;
            })
            ->keys()
            ->first();
    }

    public function getPaymentMethods(User $user): Collection {
        return $user->reweReceipts
            ->groupBy('paymentmethod')
            ->map(function($methods) {
                return $methods->count();
            })
            ->sortByDesc(function($count) {
                return $count;
            });
    }

    public function getTopMarkets(User $user): Collection {
        return $user->reweReceipts
            ->groupBy('shop_id')
            ->map(function($receipts, $shopId) {
                return collect([
                                   'shop'  => $receipts->first()->shop,
                                   'spent' => $receipts->sum('total')
                               ]);
            })
            ->sortByDesc('spent');
    }

    public function showProduct(int $id): Renderable {
        $product = ReweProduct::findOrFail($id);

        $mainStats = ReweBonPosition::whereIn('bon_id', Auth::user()->reweReceipts()->select(['id']))
                                    ->where('product_id', $product->id)
                                    ->select([
                                                 DB::raw("SUM(amount) AS amount"),
                                                 DB::raw("SUM(weight) AS weight"),
                                                 DB::raw("AVG(single_price) AS single_price")
                                             ])
                                    ->first();

        $historyQuery = ReweBonPosition::join('rewe_bons', 'rewe_bons.id', '=', 'rewe_bon_positions.bon_id')
                                       ->where('rewe_bons.user_id', Auth::user()->id)
                                       ->where('rewe_bon_positions.product_id', $product->id)
                                       ->with(['receipt'])
                                       ->orderBy('rewe_bons.timestamp_bon', 'DESC')
                                       ->select(['rewe_bon_positions.*']);

        $history    = (clone $historyQuery)->paginate(7);
        $historyAll = (clone $historyQuery)->get();

        return view('rewe_ebon.product', [
            'product'    => $product,
            'mainStats'  => $mainStats,
            'history'    => $history,
            'historyAll' => $historyAll
        ]);
    }

    public function showShop(int $id): Renderable {
        $shop = ReweShop::findOrFail($id);

        $history = ReweBon::where('user_id', Auth::user()->id)
                          ->where('shop_id', $shop->id)
                          ->orderBy('timestamp_bon', 'DESC')
                          ->select(['id', 'cashregister_nr', 'paymentmethod', 'user_id', 'shop_id', 'timestamp_bon', 'total'])
                          ->paginate(7);

        $countOther = ReweBon::where('shop_id', $shop->id)
                             ->where('user_id', '<>', Auth::user()->id)
                             ->select(['user_id'])
                             ->groupBy(['user_id'])
                             ->get()
                             ->count();

        return view('rewe_ebon.shop', [
            'shop'       => $shop,
            'history'    => $history,
            'countOther' => $countOther
        ]);
    }

}
