<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SpotifyPlayActivity extends Model {

    use HasFactory;

    protected $fillable = ['user_id', 'track_id', 'timestamp_start', 'duration', 'progress_ms', 'context_id', 'device_id'];
    protected $dates    = ['timestamp_start'];
    protected $casts    = [
        'duration'    => 'integer',
        'progress_ms' => 'integer',
    ];
    protected $appends  = ['timestamp_end'];

    public function track(): BelongsTo {
        return $this->belongsTo(SpotifyTrack::class, 'track_id', 'id');
    }

    public function device(): BelongsTo {
        return $this->belongsTo(SpotifyDevice::class, 'device_id', 'id');
    }

    public function context(): BelongsTo {
        return $this->belongsTo(SpotifyContext::class, 'context_id', 'id');
    }

    public function getTimestampEndAttribute(): Carbon {
        return $this->timestamp_start->clone()->addSeconds($this->duration);
    }
}
