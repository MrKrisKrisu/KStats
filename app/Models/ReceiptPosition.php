<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptPosition extends Model {

    use HasFactory;

    protected $fillable = ['receipt_id', 'product_id', 'amount', 'weight', 'single_price'];

    public function receipt(): BelongsTo {
        return $this->belongsTo(Receipt::class, 'receipt_id', 'id');
    }

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
