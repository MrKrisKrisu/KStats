<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReweCrowdsourcingCategory extends Model
{
    protected $fillable = ['user_id', 'product_id', 'category_id'];

    public function product() {
        return $this->belongsTo(ReweProduct::class);
    }

    public function category() {
        return $this->belongsTo(ReweProductCategory::class);
    }
}
