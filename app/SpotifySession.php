<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotifySession extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'timestamp_start', 'timestamp_end'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'user_id');
    }
}
