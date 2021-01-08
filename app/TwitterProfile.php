<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TwitterProfile extends Model {

    protected $fillable = [
        'id', 'name', 'screen_name', 'location', 'description', 'url', 'protected',
        'followers_count', 'friends_count', 'listed_count', 'statuses_count', 'account_creation'
    ];
    protected $dates    = ['account_creation'];

    public function followers(): BelongsToMany {
        return $this->belongsToMany(TwitterProfile::class, 'twitter_followers', 'followed_id', 'follower_id');
    }

    public function unfollower(): HasMany {
        return $this->hasMany(TwitterUnfollower::class, 'account_id', 'id')
                    ->orderBy('created_at', 'desc');
    }

    public function dailies(): HasMany {
        return $this->hasMany(TwitterDaily::class, 'profile_id', 'id');
    }
}
