<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyDevice extends Model {
    use HasFactory;

    protected $fillable = ['device_id', 'name', 'type', 'user_id'];
}
