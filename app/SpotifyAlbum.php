<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotifyAlbum extends Model
{

    protected $fillable = [
        'album_id', 'name', 'imageUrl', 'release_date'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function tracks()
    {
        return $this->hasMany('App\SpotifyTrack', 'album_id', 'album_id');
    }

    public function artists()
    {
        return $this->belongsToMany('App\SpotifyArtist', 'spotify_album_artists', 'album_id', 'artist_id');
    }

}