<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Pure;

class ReweBonPosition extends Model {

    protected $fillable = [
        'bon_id', 'product_id', 'amount', 'weight', 'single_price', 'grocy_transaction_id'
    ];
    protected $appends  = ['total'];

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

    #[Pure]
    #[Deprecated]
    public function total(): float {
        return $this->getTotalAttribute();
    }

    public function getTotalAttribute(): float {
        return ($this->amount ?? $this->weight) * $this->single_price;
    }
}
