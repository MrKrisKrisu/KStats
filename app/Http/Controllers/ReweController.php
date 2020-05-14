<?php

namespace App\Http\Controllers;

use App\ReweBon;
use App\ReweProduct;
use App\User;
use App\UserSettings;
use Illuminate\Http\Request;
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $bonCount = ReweBon::where('user_id', auth()->user()->id)->count();
        $avgPer = ReweBon::where('user_id', auth()->user()->id)->select(DB::raw('AVG(total) as total'))->first()->total;
        $mostUsedPaymentMethod = ReweBon::where('user_id', auth()->user()->id)->groupBy('paymentmethod')
            ->select('paymentmethod', DB::raw("COUNT(*) as cnt"))
            ->orderBy('cnt', 'DESC')->first();
        $mostUsedPaymentMethod = $mostUsedPaymentMethod == NULL ? '¯\_(ツ)_/¯' : $mostUsedPaymentMethod->paymentmethod;
        $total = ReweBon::where('user_id', auth()->user()->id)->select(DB::raw('SUM(total) as total'))->first()->total;

        $favouriteProducts = DB::table('rewe_bons')
            ->join('rewe_bon_positions', 'rewe_bon_positions.bon_id', 'rewe_bons.id')
            ->join('rewe_products', 'rewe_products.id', 'rewe_bon_positions.product_id')
            ->where('rewe_bons.user_id', auth()->user()->id)
            ->groupBy('rewe_products.id')
            ->select('rewe_products.*', DB::raw('COUNT(*) as cnt'))
            ->orderByDesc('cnt')
            ->limit(5)
            ->get();

        $shoppingByHour = ReweBon::where('user_id', auth()->user()->id)
            ->groupBy(DB::raw("HOUR(timestamp_bon)"))
            ->select(DB::raw('HOUR(timestamp_bon) as hour'), DB::raw('COUNT(*) as cnt'))
            ->get();

        $bonList = ReweBon::where('user_id', auth()->user()->id)->orderByDesc('timestamp_bon')->get();

        $shops = ReweBon::where('user_id', auth()->user()->id)
            ->groupBy('shop_id')
            ->select('shop_id', DB::raw('COUNT(*) as cnt'))
            ->orderBy(DB::raw('COUNT(*)'), 'desc')
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
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->get();


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
            ->orderByDesc(DB::raw('SUM(rewe_bon_positions.single_price)'))
            ->get();

        return view('rewe_ebon.overview', [
            'bonCount' => $bonCount,
            'avgPer' => $avgPer,
            'mostUsedPaymentMethod' => $mostUsedPaymentMethod,
            'products_vegetarian' => $products_vegetarian,
            'total' => $total,
            'favouriteProducts' => $favouriteProducts,
            'bonList' => $bonList,
            'shoppingByHour' => $shoppingByHour,
            'shops' => $shops,
            'payment_methods' => $payment_methods,
            'forecast' => $forecast,
            'topByCategoryCount' => $topByCategoryCount,
            'topByCategoryPrice' => $topByCategoryPrice,
            'ebonKey' => UserSettings::get(auth()->user()->id, 'eBonKey', md5(rand(0, 99) . time()))
        ]);
    }

    public function downloadRawReceipt(int $receipt_id)
    {
        $receipt = ReweBon::find($receipt_id);

        if ($receipt == NULL || $receipt->user_id != auth()->user()->id)
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
        if ($key !== NULL)
            return $key->val;

        $key = md5(auth()->user()->id . time() . rand(1, 99));

        UserSettings::create([
            'user_id' => auth()->user()->id,
            'name' => 'rewe_ebon_key',
            'val' => $key
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
