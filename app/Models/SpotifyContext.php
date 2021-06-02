<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyContext extends Model {

    use HasFactory;

    protected $fillable = ['uri'];
}
