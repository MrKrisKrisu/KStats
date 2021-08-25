<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicTransportComplaint extends Model {

    use HasFactory;

    protected $fillable = ['user_id', 'journey_id', 'card_id', 'date', 'cashback'];
    protected $dates    = ['date'];
    protected $casts    = [
        'user_id'    => 'integer',
        'journey_id' => 'integer',
        'card_id'    => 'integer',
        'cashback'   => 'double',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function journey(): BelongsTo {
        return $this->belongsTo(PublicTransportJourney::class, 'journey_id', 'id');
    }

    public function card(): BelongsTo {
        return $this->belongsTo(PublicTransportCard::class, 'card_id', 'id');
    }
}
