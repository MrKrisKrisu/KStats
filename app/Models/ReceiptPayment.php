<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptPayment extends Model {

    use HasFactory;

    protected $fillable = ['receipt_id', 'payment_method_id', 'amount'];

    public function receipt(): BelongsTo {
        return $this->belongsTo(Receipt::class, 'receipt_id', 'id');
    }

    public function paymentMethod(): BelongsTo {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
}
