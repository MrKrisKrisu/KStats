<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReweCrowdsourcingCategory extends Model {

    protected $fillable = ['user_id', 'product_id', 'category_id'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo {
        return $this->belongsTo(ReweProduct::class);
    }

    public function category(): BelongsTo {
        return $this->belongsTo(ReweProductCategory::class);
    }
}
