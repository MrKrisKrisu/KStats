<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotifyArtist extends Model
{
    protected $fillable = ['artist_id', 'name'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function tracks()
    {
        return $this->belongsToMany(SpotifyTrack::class, 'spotify_track_artists', 'artist_id', 'track_id');
    }
}
