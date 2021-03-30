<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpotifyAlbum extends Model {

    use HasFactory;

    protected $fillable = ['album_id', 'name', 'imageUrl', 'release_date'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $dates    = ['release_date'];

    public function tracks(): HasMany {
        return $this->hasMany(SpotifyTrack::class, 'album_id', 'album_id');
    }

    public function artists(): BelongsToMany {
        return $this->belongsToMany(SpotifyArtist::class, 'spotify_album_artists', 'album_id', 'artist_id');
    }

}