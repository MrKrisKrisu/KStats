<?php

namespace App\Http\Controllers;

use App\Models\ReweCrowdsourcingCategory;
use App\Models\ReweCrowdsourcingVegetarian;
use App\Models\ReweProduct;
use App\Models\ReweProductCategory;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CrowdsourceController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @return Renderable
     */
    public function renderRewe(): Renderable {
        $categories = ReweProductCategory::with(['parent'])->where('parent_id', '<>', null)->get();

        $nextProductAtCategory = ReweProduct::join('rewe_bon_positions', 'rewe_bon_positions.product_id', '=', 'rewe_products.id')
                                            ->join('rewe_bons', 'rewe_bon_positions.bon_id', '=', 'rewe_bons.id')
                                            ->where('rewe_bons.user_id', auth()->user()->id)
                                            ->where('rewe_products.hide', 0)
                                            ->whereNotIn('rewe_products.id', function($query) {
                                                $query->select('product_id')
                                                      ->from('rewe_crowdsourcing_categories')
                                                      ->where('user_id', auth()->user()->id);
                                            })
                                            ->groupBy('rewe_products.id')
                                            ->select(['rewe_products.*', DB::raw('MAX(rewe_bons.timestamp_bon) AS lastReceipt')])
                                            ->orderByDesc(DB::raw('MAX(rewe_bons.timestamp_bon) AS lastReceipt'))
                                            ->limit(1)
                                            ->first();

        $lastCategories = ReweCrowdsourcingCategory::with(['category', 'product'])->where('user_id', auth()->user()->id)->orderByDesc('created_at')->limit(7)->get();


        $nextProductAtVegetarian = ReweProduct::join('rewe_bon_positions', 'rewe_bon_positions.product_id', '=', 'rewe_products.id')
                                              ->join('rewe_bons', 'rewe_bon_positions.bon_id', '=', 'rewe_bons.id')
                                              ->where('rewe_bons.user_id', auth()->user()->id)
                                              ->where('rewe_products.hide', 0)
                                              ->whereNotIn('rewe_products.id', function($query) {
                                                  $query->select('product_id')
                                                        ->from('rewe_crowdsourcing_vegetarians')
                                                        ->where('user_id', auth()->user()->id);
                                              })
                                              ->groupBy('rewe_products.id')
                                              ->select(['rewe_products.*', DB::raw('MAX(rewe_bons.timestamp_bon) AS lastReceipt')])
                                              ->limit(1)
                                              ->first();


        $lastVegetarians = ReweCrowdsourcingVegetarian::with(['product'])->where('user_id', auth()->user()->id)->orderByDesc('created_at')->limit(7)->get();

        return view('crowdsourcing.rewe', [
            'categories'         => $categories,
            'categories_product' => $nextProductAtCategory,
            'lastCategories'     => $lastCategories,
            'vegetarian_product' => $nextProductAtVegetarian,
            'lastVegetarians'    => $lastVegetarians
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Renderable
     */
    public function handleSubmit(Request $request): Renderable {

        switch($request->action) {
            case 'deleteCategory':
                $validated = $request->validate([
                                                    'product_id' => ['required', 'integer', 'exists:rewe_products,id']
                                                ]);

                ReweCrowdsourcingCategory::where('user_id', auth()->user()->id)
                                         ->where('product_id', $validated['product_id'])
                                         ->delete();
                break;

            case 'setCategory':
                $validated = $request->validate([
                                                    'btn'         => ['required'],
                                                    'product_id'  => ['required', 'integer', 'exists:rewe_products,id'],
                                                    'category_id' => ['required', 'integer', 'exists:rewe_product_categories,id']
                                                ]);

                ReweCrowdsourcingCategory::create([
                                                      'user_id'     => Auth::user()->id,
                                                      'product_id'  => $validated['product_id'],
                                                      'category_id' => $validated['btn'] == 'ka' ? null : $validated['category_id']
                                                  ]);
                break;

            case 'deleteVegetarian':
                $validated = $request->validate([
                                                    'product_id' => ['required', 'integer', 'exists:rewe_products,id']
                                                ]);

                ReweCrowdsourcingVegetarian::where('user_id', auth()->user()->id)
                                           ->where('product_id', $validated['product_id'])
                                           ->delete();
                break;

            case 'setVegetarian':
                $validated = $request->validate([
                                                    'setVegetarian' => ['required', 'in:ka,1,0,-1'],
                                                    'product_id'    => ['required', 'integer', 'exists:rewe_products,id']
                                                ]);

                ReweCrowdsourcingVegetarian::create([
                                                        'user_id'    => Auth::user()->id,
                                                        'product_id' => $validated['product_id'],
                                                        'vegetarian' => $validated['setVegetarian'] == 'ka' ? null : $validated['setVegetarian']
                                                    ]);
                break;

        }


        return $this->renderRewe();
    }
}
