<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotifyPlayActivity extends Model
{

    protected $fillable = ['user_id', 'track_id', 'timestamp_start', 'progress_ms', 'context', 'context_uri', 'device_id'];
    protected $dates = ['timestamp_start'];

    public function track()
    {
        return $this->belongsTo('App\SpotifyTrack', 'track_id', 'track_id');
    }

    public function device()
    {
        return $this->belongsTo('App\SpotifyDevice', 'device_id', 'id');
    }

}
