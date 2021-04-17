<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Model {

    use HasFactory;

    protected $fillable = ['name'];

    public function products(): HasMany {
        return $this->hasMany(Product::class, 'product_type_id', 'id');
    }
}
