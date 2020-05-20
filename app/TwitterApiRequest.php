<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwitterApiRequest extends Model
{
    protected $fillable = ['twitter_profile_id', 'endpoint'];

}
