<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SpotifyArtist extends Model {

    protected $fillable = ['artist_id', 'name'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function tracks(): BelongsToMany {
        return $this->belongsToMany(SpotifyTrack::class, 'spotify_track_artists', 'artist_id', 'track_id');
    }
}
