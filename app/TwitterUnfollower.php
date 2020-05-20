<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwitterUnfollower extends Model
{
    protected $fillable = ['unfollower_id', 'account_id'];

    public function twitter_profile()
    {
        return $this->belongsTo(TwitterProfile::class, 'account_id', 'id');
    }

    public function unfollower_profile()
    {
        return $this->belongsTo(TwitterProfile::class, 'unfollower_id', 'id');
    }
}