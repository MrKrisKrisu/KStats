<?php

namespace App\Http\Controllers;

use App\ReweCrowdsourcingCategory;
use App\ReweCrowdsourcingVegetarian;
use App\ReweProduct;
use App\ReweProductCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrowdsourceController extends Controller
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function renderRewe()
    {
        $categories = ReweProductCategory::where('parent_id', '<>', null)->get();

        $nextProductAtCategory = ReweProduct::join('rewe_bon_positions', 'rewe_bon_positions.product_id', '=', 'rewe_products.id')
            ->join('rewe_bons', 'rewe_bon_positions.bon_id', '=', 'rewe_bons.id')
            ->where('rewe_bons.user_id', auth()->user()->id)
            ->where('rewe_products.hide', 0)
            ->whereNotIn('rewe_products.id', function ($query) {
                $query->select('product_id')
                    ->from('rewe_crowdsourcing_categories')
                    ->where('user_id', auth()->user()->id);
            })
            ->groupBy('rewe_products.id')
            ->select(['rewe_products.*', DB::raw('MAX(rewe_bons.timestamp_bon) AS lastReceipt')])
            ->limit(1)
            ->first();

        $lastCategories = ReweCrowdsourcingCategory::where('user_id', auth()->user()->id)->orderByDesc('created_at')->limit(7)->get();


        $nextProductAtVegetarian = ReweProduct::join('rewe_bon_positions', 'rewe_bon_positions.product_id', '=', 'rewe_products.id')
            ->join('rewe_bons', 'rewe_bon_positions.bon_id', '=', 'rewe_bons.id')
            ->where('rewe_bons.user_id', auth()->user()->id)
            ->where('rewe_products.hide', 0)
            ->whereNotIn('rewe_products.id', function ($query) {
                $query->select('product_id')
                    ->from('rewe_crowdsourcing_vegetarians')
                    ->where('user_id', auth()->user()->id);
            })
            ->groupBy('rewe_products.id')
            ->select(['rewe_products.*', DB::raw('MAX(rewe_bons.timestamp_bon) AS lastReceipt')])
            ->limit(1)
            ->first();


        $lastVegetarians = ReweCrowdsourcingVegetarian::where('user_id', auth()->user()->id)->orderByDesc('created_at')->limit(7)->get();

        return view('crowdsourcing.rewe', [
            'categories' => $categories,
            'categories_product' => $nextProductAtCategory,
            'lastCategories' => $lastCategories,
            'vegetarian_product' => $nextProductAtVegetarian,
            'lastVegetarians' => $lastVegetarians
        ]);
    }

    public function handleSubmit(Request $request)
    {

        switch ($request->action) {
            case 'deleteCategory':
                ReweCrowdsourcingCategory::where('user_id', auth()->user()->id)
                    ->where('product_id', $request->product_id)
                    ->delete();
                break;

            case 'setCategory':
                ReweCrowdsourcingCategory::create([
                    'user_id' => auth()->user()->id,
                    'product_id' => $request->product_id,
                    'category_id' => $request->btn == 'ka' ? NULL : $request->category_id
                ]);
                break;

            case 'deleteVegetarian':
                ReweCrowdsourcingVegetarian::where('user_id', auth()->user()->id)
                    ->where('product_id', $request->product_id)
                    ->delete();
                break;

            case 'setVegetarian':
                ReweCrowdsourcingVegetarian::create([
                    'user_id' => auth()->user()->id,
                    'product_id' => $request->product_id,
                    'vegetarian' => $request->setVegetarian == 'ka' ? NULL : $request->setVegetarian
                ]);
                break;

        }


        return $this->renderRewe();
    }
}
