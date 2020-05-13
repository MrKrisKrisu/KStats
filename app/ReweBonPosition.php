<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReweBonPosition extends Model
{
    protected $fillable = [
        'bon_id', 'product_id', 'amount', 'weight', 'single_price'
    ];

    public function bon()
    {
        return $this->belongsTo('App\ReweBon', 'id', 'bon_id');
    }

    public function product()
    {
        return $this->hasOne("App\ReweProduct", 'id', 'product_id');
    }

    public function total()
    {
        return ($this->amount ?? $this->weight) * $this->single_price;
    }
}
