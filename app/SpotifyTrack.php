<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SpotifyTrack extends Model {

    use HasFactory;

    protected $fillable = [
        'track_id', 'name', 'album_id', 'preview_url', 'popularity', 'explicit', 'duration_ms', 'danceability',
        'energy', 'loudness', 'speechiness', 'acousticness', 'instrumentalness', 'valence', 'key', 'mode', 'bpm'
    ];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $appends  = ['spotify_link'];

    public function album(): HasOne {
        return $this->hasOne(SpotifyAlbum::class, 'album_id', 'album_id');
    }

    public function artists(): BelongsToMany {
        return $this->belongsToMany(SpotifyArtist::class, 'spotify_track_artists', 'track_id', 'artist_id');
    }

    public function getSpotifyLinkAttribute(): string {
        return strtr('https://open.spotify.com/track/:trackId', [
            ':trackId' => $this->track_id
        ]);
    }

}
