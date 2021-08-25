<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicTransportJourney extends Model {

    use HasFactory;

    protected $fillable = ['public_transport_card_id', 'origin', 'destination', 'price_without_card', 'price_with_card'];
    protected $appends  = ['saved'];
    protected $casts    = [
        'public_transport_card_id' => 'integer',
        'price_without_card'       => 'float',
        'price_with_card'          => 'float',
    ];

    public function card(): BelongsTo {
        return $this->belongsTo(PublicTransportCard::class, 'public_transport_card_id', 'id');
    }

    public function getSavedAttribute(): float {
        return $this->price_without_card - $this->price_with_card;
    }
}
