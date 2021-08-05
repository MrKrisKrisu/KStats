<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReweCrowdsourcingVegetarian extends Model {
    protected $fillable = ['user_id', 'product_id', 'vegetarian'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo {
        return $this->belongsTo(ReweProduct::class);
    }
}
