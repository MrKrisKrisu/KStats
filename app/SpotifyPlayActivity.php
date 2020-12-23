<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpotifyPlayActivity extends Model {

    protected $fillable = ['user_id', 'track_id', 'timestamp_start', 'progress_ms', 'context_id', 'device_id'];
    protected $dates    = ['timestamp_start'];

    public function track(): BelongsTo {
        return $this->belongsTo(SpotifyTrack::class, 'track_id', 'track_id');
    }

    public function device(): BelongsTo {
        return $this->belongsTo(SpotifyDevice::class, 'device_id', 'id');
    }
}
