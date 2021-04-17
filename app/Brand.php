<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model {

    use HasFactory;

    protected $fillable = ['name', 'wikidata_id'];

    public function shops(): HasMany {
        return $this->hasMany(Shop::class, 'brand_id', 'id');
    }
}
