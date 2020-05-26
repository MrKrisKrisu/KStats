<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReweBon extends Model
{

    protected $fillable = [
        'user_id', 'shop_id', 'timestamp_bon', 'bon_nr', 'cashier_nr', 'cashregister_nr', 'paymentmethod',
        'payed_cashless', 'payed_contactless', 'total', 'earned_payback_points', 'receipt_pdf'
    ];

    protected $dates = ['timestamp_bon'];

    protected $appends = ['cashback_rate'];


    public function positions()
    {
        return $this->hasMany('App\ReweBonPosition', 'bon_id', 'id');
    }

    public function shop()
    {
        return $this->hasOne('App\ReweShop', 'id', 'shop_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returnes the cashback rate in relation to the total amount in percent
     * @return float
     */
    public function getCashbackRateAttribute()
    {
        return round($this->earned_payback_points / ($this->total * 100) * 100, 2);
    }
}
