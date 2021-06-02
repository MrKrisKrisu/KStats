<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReweProduct extends Model {

    protected $fillable = ['name'];

    public function category(): BelongsToMany {
        return $this->belongsToMany(ReweProductCategory::class, 'rewe_crowdsourcing_categories_view', 'product_id', 'category_id');
    }
}
