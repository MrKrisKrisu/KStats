<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialLoginProfile extends Model
{
    protected $fillable = [
        'user_id', 'twitter_id', 'twitter_token', 'twitter_tokenSecret', 'spotify_accessToken', 'spotify_refreshToken',
        'spotify_lastRefreshed', 'spotify_user_id'
    ];
    protected $hidden = ['twitter_token', 'twitter_tokenSecret', 'spotify_accessToken', 'spotify_refreshToken'];
    protected $appends = ['isConnectedSpotify', 'isConnectedTwitter', 'isConnectedTelegram'];
    protected $dates = ['spotify_lastRefreshed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function twitterUser()
    {
        return $this->belongsTo(TwitterProfile::class, 'twitter_id', 'id');
    }

    public function getIsConnectedSpotifyAttribute()
    {
        if ($this->spotify_accessToken == null || $this->spotify_refreshToken == null || $this->spotify_user_id == null || $this->spotify_lastRefreshed == null)
            return false;
        if ($this->spotify_lastRefreshed->diffInMinutes() > 120)
            return false;
        //TODO: Check if Token is valid
        return true;
    }

    public function getIsConnectedTwitterAttribute()
    {
        if ($this->twitter_id == null || $this->twitter_token == null || $this->twitter_tokenSecret == null)
            return false;
        //TODO: Check if Token is valid
        return true;
    }

    public function getIsConnectedTelegramAttribute()
    {
        //TODO: Telegram ID should be moved here from Settings
        if (UserSettings::get($this->user->id, 'telegram_id') == null)
            return false;
        //TODO: Check if ID is valid and not blocked
        return true;
    }

}
