<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReweProduct extends Model
{

    protected $fillable = [
        'name'
    ];

    public function category()
    {
        return $this->belongsToMany('App\ReweProductCategory', 'rewe_crowdsourcing_categories_view', 'product_id', 'category_id');
    }
}
