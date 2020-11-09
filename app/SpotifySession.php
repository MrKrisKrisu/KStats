<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotifySession extends Model
{

    protected $fillable = ['user_id', 'timestamp_start', 'timestamp_end'];
    protected $dates    = ['timestamp_start', 'timestamp_end'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
