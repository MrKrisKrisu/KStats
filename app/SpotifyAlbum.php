<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyAlbum extends Model {
    use HasFactory;

    protected $fillable = ['album_id', 'name', 'imageUrl', 'release_date'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $dates    = ['release_date'];

    public function tracks() {
        return $this->hasMany(SpotifyTrack::class, 'album_id', 'album_id');
    }

    public function artists() {
        return $this->belongsToMany(SpotifyArtist::class, 'spotify_album_artists', 'album_id', 'artist_id');
    }

}