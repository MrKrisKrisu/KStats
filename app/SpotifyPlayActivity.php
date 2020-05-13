<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotifyPlayActivity extends Model {

    protected $fillable = [
        'user_id', 'track_id', 'timestamp_start', 'progress_ms', 'context', 'context_uri', 'device_id'
    ];

    public function track() {
        return $this->belongsTo('App\SpotifyTrack', 'track_id', 'track_id');
    }

}
