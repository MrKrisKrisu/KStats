<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpotifyTrackRating extends Model {

    use HasFactory;

    protected $fillable = ['user_id', 'track_id', 'rating'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function track(): BelongsTo {
        return $this->belongsTo(SpotifyTrack::class, 'track_id', 'id');
    }
}
