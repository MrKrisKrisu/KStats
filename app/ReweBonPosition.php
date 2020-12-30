<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReweBonPosition extends Model {

    protected $fillable = [
        'bon_id', 'product_id', 'amount', 'weight', 'single_price'
    ];

    /**
     * @deprecated use ->receipt
     */
    public function bon(): BelongsTo {
        return $this->belongsTo(ReweBon::class, 'bon_id', 'id');
    }

    public function receipt(): BelongsTo {
        return $this->belongsTo(ReweBon::class, 'bon_id', 'id');
    }

    public function product(): HasOne {
        return $this->hasOne(ReweProduct::class, 'id', 'product_id');
    }

    public function total(): float {
        return ($this->amount ?? $this->weight) * $this->single_price;
    }
}
