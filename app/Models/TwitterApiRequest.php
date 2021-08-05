<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwitterApiRequest extends Model {
    protected $fillable = ['twitter_profile_id', 'endpoint'];

}
