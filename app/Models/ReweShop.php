<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReweShop extends Model {

    protected $fillable = [
        'id', 'name', 'brand_id', 'address', 'zip', 'city', 'phone', 'opening_hours'
    ];
    protected $casts    = [
        'brand_id' => 'integer',
    ];

    public function brand(): BelongsTo {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}
