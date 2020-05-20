<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialLoginProfile extends Model
{
    protected $fillable = [
        'user_id', 'twitter_id', 'twitter_token', 'twitter_tokenSecret', 'spotify_accessToken', 'spotify_refreshToken',
        'spotify_lastRefreshed', 'spotify_user_id'
    ];
    protected $hidden = [
        'twitter_token', 'twitter_tokenSecret', 'spotify_accessToken', 'spotify_refreshToken'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function twitterUser()
    {
        return $this->belongsTo(TwitterProfile::class, 'twitter_id', 'id');
    }

}
