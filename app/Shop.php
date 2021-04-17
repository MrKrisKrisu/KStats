<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model {

    use HasFactory;

    protected $fillable = ['brand_id', 'name', 'address', 'postal_code', 'city', 'osm_type', 'osm_id'];

    public function brand(): BelongsTo {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function receipts(): HasMany {
        return $this->hasMany(Receipt::class, 'shop_id', 'id');
    }
}
