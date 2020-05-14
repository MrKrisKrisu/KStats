<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotifyTrack extends Model
{

    protected $fillable = [
        'track_id', 'name', 'album_id', 'preview_url', 'popularity', 'explicit', 'duration_ms', 'danceability',
        'energy', 'loudness', 'speechiness', 'acousticness', 'instrumentalness', 'valence', 'key', 'mode', 'bpm'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function album()
    {
        return $this->hasOne('App\SpotifyAlbum', 'album_id', 'album_id');
    }

    public function artists()
    {
        return $this->belongsToMany('App\SpotifyArtist', 'spotify_track_artists', 'track_id', 'artist_id');
    }

}
