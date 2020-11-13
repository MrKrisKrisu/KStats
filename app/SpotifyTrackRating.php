<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyTrackRating extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'track_id', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function track()
    {
        return $this->belongsTo(SpotifyTrack::class, 'track_id', 'id');
    }
}
