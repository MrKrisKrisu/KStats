<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotifyTrack extends Model
{

    protected $fillable = [
        'track_id', 'name', 'album_id', 'preview_url', 'popularity', 'explicit', 'duration_ms', 'danceability',
        'energy', 'loudness', 'speechiness', 'acousticness', 'instrumentalness', 'valence', 'key', 'mode', 'bpm'
    ];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $appends  = ['spotify_link'];

    public function album()
    {
        return $this->hasOne(SpotifyAlbum::class, 'album_id', 'album_id');
    }

    public function artists()
    {
        return $this->belongsToMany(SpotifyArtist::class, 'spotify_track_artists', 'track_id', 'artist_id');
    }

    public function getSpotifyLinkAttribute()
    {
        return strtr('https://open.spotify.com/track/:trackId', [
            ':trackId' => $this->track_id
        ]);
    }

}
