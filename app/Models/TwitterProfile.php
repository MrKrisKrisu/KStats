<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwitterProfile extends Model {

    protected $fillable = [
        'id', 'name', 'screen_name', 'location', 'description', 'url', 'profile_image_url', 'protected',
        'followers_count', 'friends_count', 'listed_count', 'statuses_count', 'account_creation'
    ];
    protected $dates    = ['account_creation'];

    public function socialProfile(): BelongsTo {
        return $this->belongsTo(SocialLoginProfile::class, 'id', 'twitter_id');
    }

    public function followers(): BelongsToMany {
        return $this->belongsToMany(__CLASS__, 'twitter_followers', 'followed_id', 'follower_id');
    }

    public function unfollower(): HasMany {
        return $this->hasMany(TwitterUnfollower::class, 'account_id', 'id')
                    ->orderBy('created_at', 'desc');
    }

    public function dailies(): HasMany {
        return $this->hasMany(TwitterDaily::class, 'profile_id', 'id');
    }
}
