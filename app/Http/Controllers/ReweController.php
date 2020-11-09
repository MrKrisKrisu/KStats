<?php

namespace App\Http\Controllers;

use App\ReweBon;
use App\UserSettings;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ReweController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        auth()->user()->load(['reweReceipts', 'reweReceipts.shop']);

        $mostUsedPaymentMethod = ReweBon::where('user_id', auth()->user()->id)->groupBy('paymentmethod')
                                        ->select('paymentmethod', DB::raw("COUNT(*) as cnt"))
                                        ->orderBy('cnt', 'DESC')->first();
        $mostUsedPaymentMethod = $mostUsedPaymentMethod == null ? '¯\_(ツ)_/¯' : $mostUsedPaymentMethod->paymentmethod;

        $favouriteProducts = DB::table('rewe_bons')
                               ->join('rewe_bon_positions', 'rewe_bon_positions.bon_id', 'rewe_bons.id')
                               ->join('rewe_products', 'rewe_products.id', 'rewe_bon_positions.product_id')
                               ->where('rewe_bons.user_id', auth()->user()->id)
                               ->groupBy('rewe_products.id')
                               ->select('rewe_products.*', DB::raw('COUNT(*) as cnt'))
                               ->orderByDesc('cnt')
                               ->limit(5)
                               ->get();

        $payment_methods = ReweBon::where('user_id', auth()->user()->id)
                                  ->groupBy('paymentmethod')
                                  ->select('paymentmethod', DB::raw('COUNT(*) as cnt'))
                                  ->orderBy(DB::raw('COUNT(*)'), 'desc')
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

        $forecast = self::getForecast();

        $topByCategoryCount = DB::table('rewe_products')
                                ->where('rewe_bons.user_id', auth()->user()->id)
                                ->join('rewe_bon_positions', 'rewe_bon_positions.product_id', '=', 'rewe_products.id')
                                ->join('rewe_bons', 'rewe_bons.id', '=', 'rewe_bon_positions.bon_id')
                                ->join('rewe_product_categories_view', 'rewe_product_categories_view.product_id', '=', 'rewe_products.id')
                                ->join('rewe_product_categories', 'rewe_product_categories_view.category_id', '=', 'rewe_product_categories.id')
                                ->groupBy('rewe_product_categories.id')
                                ->select([
                                             DB::raw('rewe_product_categories.id AS category_id'),
                                             DB::raw('rewe_product_categories.name AS category_name'),
                                             DB::raw('COUNT(*) AS cnt')
                                         ])
                                ->orderByDesc(DB::raw('COUNT(*)'))//->get()
        ;


        $topByCategoryPrice = DB::table('rewe_products')
                                ->where('rewe_bons.user_id', auth()->user()->id)
                                ->join('rewe_bon_positions', 'rewe_bon_positions.product_id', '=', 'rewe_products.id')
                                ->join('rewe_bons', 'rewe_bons.id', '=', 'rewe_bon_positions.bon_id')
                                ->join('rewe_product_categories_view', 'rewe_product_categories_view.product_id', '=', 'rewe_products.id')
                                ->join('rewe_product_categories', 'rewe_product_categories_view.category_id', '=', 'rewe_product_categories.id')
                                ->groupBy('rewe_product_categories.id')
                                ->select([
                                             DB::raw('rewe_product_categories.id AS category_id'),
                                             DB::raw('rewe_product_categories.name AS category_name'),
                                             DB::raw('SUM(rewe_bon_positions.single_price) AS price')
                                         ])
                                ->orderByDesc(DB::raw('SUM(rewe_bon_positions.single_price)'))//->get()
        ;

        $monthlySpend = auth()->user()->reweReceipts->groupBy(function ($receipt) {
            return $receipt->timestamp_bon->format('m.Y');
        })->map(function ($receipts) {
            return $receipts->sum('total');
        });

        return view('rewe_ebon.overview', [
            'mostUsedPaymentMethod' => $mostUsedPaymentMethod,
            'products_vegetarian'   => $products_vegetarian,
            'favouriteProducts'     => $favouriteProducts,
            'payment_methods'       => $payment_methods,
            'forecast'              => $forecast,
            'topByCategoryCount'    => [],
            'topByCategoryPrice'    => [],
            'ebonKey'               => UserSettings::get(auth()->user()->id, 'eBonKey', md5(rand(0, 99) . time())),
            'monthlySpend'          => $monthlySpend
        ]);
    }

    public function downloadRawReceipt(int $receipt_id)
    {
        $receipt = ReweBon::find($receipt_id);

        if ($receipt == null || $receipt->user_id != auth()->user()->id)
            return response("No permission", 401);

        return response($receipt->receipt_pdf, 200)
            ->header('Content-Type', 'application/pdf');
    }

    public function renderBonDetails(int $receipt_id)
    {
        $bon = ReweBon::find($receipt_id);

        if ($bon->user->id != auth()->user()->id)
            return Redirect::route('rewe')->withErrors(['msg', 'No Permissions to access this bon.']);

        return view('rewe_ebon.receipt_details', [
            'bon' => $bon
        ]);
    }

    public static function getMailKey()
    {
        $key = UserSettings::where('user_id', auth()->user()->id)->where('name', 'rewe_ebon_key')->first();
        if ($key !== null)
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
     * @return \Illuminate\Support\Collection
     */
    public static function getForecast()
    {
        return DB::table('rewe_bons')
                 ->join('rewe_bon_positions', 'rewe_bons.id', '=', 'rewe_bon_positions.bon_id')
                 ->join('rewe_products', 'rewe_bon_positions.product_id', '=', 'rewe_products.id')
                 ->where('rewe_bons.user_id', auth()->user()->id)
                 ->groupBy('rewe_bon_positions.product_id')
                 ->havingRaw('cnt > 2 AND nextTS > (NOW() - INTERVAL 10 DAY)')
                 ->select([
                              'rewe_products.*',
                              DB::raw('MAX(rewe_bons.timestamp_bon) AS lastTS'),
                              DB::raw('MIN(rewe_bons.timestamp_bon) AS firstTS'),
                              DB::raw('COUNT(rewe_bons.timestamp_bon) AS cnt'),
                              DB::raw('TIMESTAMPDIFF(HOUR, MIN(rewe_bons.timestamp_bon), MAX(rewe_bons.timestamp_bon)) / COUNT(rewe_bons.timestamp_bon) AS avgHours'),
                              DB::raw('TIMESTAMPADD(HOUR, TIMESTAMPDIFF(HOUR, MIN(rewe_bons.timestamp_bon), MAX(rewe_bons.timestamp_bon)) / COUNT(rewe_bons.timestamp_bon), MAX(rewe_bons.timestamp_bon) ) AS nextTS')
                          ])
                 ->orderBy('nextTS')
                 ->get();
    }

}
