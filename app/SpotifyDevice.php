<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotifyDevice extends Model
{
    protected $fillable = ['device_id', 'name', 'type', 'user_id'];
}
