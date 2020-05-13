<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReweCrowdsourcingVegetarian extends Model
{
    protected $fillable = ['user_id', 'product_id', 'vegetarian'];

    public function product()
    {
        return $this->belongsTo(ReweProduct::class);
    }
}
