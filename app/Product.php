<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model {

    use HasFactory;

    protected $fillable = ['name', 'user_id', 'product_type_id'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function productType(): BelongsTo {
        return $this->belongsTo(Product::class, 'product_type_id', 'id');
    }

}
