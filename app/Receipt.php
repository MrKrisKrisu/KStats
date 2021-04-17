<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receipt extends Model {

    use HasFactory;

    protected $fillable = [
        'user_id', 'shop_id', 'timestamp', 'receipt_id', 'cashier_nr',
        'cash_register_nr', 'amount', 'earned_loyalty_points', 'raw_receipt'
    ];
    protected $dates    = ['timestamp'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function shop(): BelongsTo {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function payments(): HasMany {
        return $this->hasMany(ReceiptPayment::class, 'receipt_id', 'id');
    }

    public function positions(): HasMany {
        return $this->hasMany(ReceiptPosition::class, 'receipt_id', 'id');
    }
}
