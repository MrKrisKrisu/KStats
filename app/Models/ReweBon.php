<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReweBon extends Model {

    protected $fillable = [
        'user_id', 'shop_id', 'timestamp_bon', 'bon_nr', 'cashier_nr', 'cashregister_nr', 'paymentmethod',
        'payed_cashless', 'payed_contactless', 'total', 'earned_payback_points', 'receipt_pdf'
    ];

    protected $dates   = ['timestamp_bon'];
    protected $appends = ['cashback_rate'];

    public function positions(): HasMany {
        return $this->hasMany(ReweBonPosition::class, 'bon_id', 'id');
    }

    public function shop(): HasOne {
        return $this->hasOne(ReweShop::class, 'id', 'shop_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Returnes the cashback rate in relation to the total amount in percent
     * @return float
     */
    public function getCashbackRateAttribute(): float {
        return round($this->earned_payback_points / ($this->total * 100) * 100, 2);
    }
}
