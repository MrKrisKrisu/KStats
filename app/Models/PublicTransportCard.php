<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PublicTransportCard extends Model {

    use HasFactory;

    protected $fillable = ['user_id', 'description', 'valid_from', 'valid_to', 'cost'];
    protected $dates    = ['valid_from', 'valid_to'];
    protected $casts    = [
        'user_id' => 'integer',
        'cost'    => 'float',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function journeys(): HasMany {
        return $this->hasMany(PublicTransportJourney::class, 'public_transport_card_id', 'id');
    }
}
