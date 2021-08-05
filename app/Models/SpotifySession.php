<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpotifySession extends Model {

    protected $fillable = ['user_id', 'timestamp_start', 'timestamp_end'];
    protected $dates    = ['timestamp_start', 'timestamp_end'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
