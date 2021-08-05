<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyGenre extends Model {

    use HasFactory;

    protected $fillable = ['seed', 'display_name'];
}
