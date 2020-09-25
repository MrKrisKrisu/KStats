<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwitterProfile extends Model
{
    protected $fillable = [
        'id', 'name', 'screen_name', 'location', 'description', 'url', 'protected',
        'followers_count', 'friends_count', 'listed_count', 'statuses_count', 'account_creation'
    ];
    protected $dates = ['account_creation'];

    public function followers()
    {
        return $this->belongsToMany(TwitterProfile::class, 'twitter_followers', 'followed_id', 'follower_id');
    }

    public function unfollower()
    {
        return $this->hasMany(TwitterUnfollower::class, 'account_id', 'id')->orderBy('created_at', 'desc');
    }
}
