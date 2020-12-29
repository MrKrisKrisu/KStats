<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Telegram\Bot\Laravel\Facades\Telegram;

class SocialLoginProfile extends Model {

    protected $fillable = [
        'user_id', 'telegram_id', 'twitter_id', 'twitter_token', 'twitter_tokenSecret', 'spotify_accessToken', 'spotify_refreshToken',
        'spotify_lastRefreshed', 'spotify_user_id'
    ];
    protected $hidden   = ['twitter_token', 'twitter_tokenSecret', 'spotify_accessToken', 'spotify_refreshToken'];
    protected $appends  = ['isConnectedSpotify', 'isConnectedTwitter', 'isConnectedTelegram'];
    protected $dates    = ['spotify_lastRefreshed'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function twitterUser(): BelongsTo {
        return $this->belongsTo(TwitterProfile::class, 'twitter_id', 'id');
    }

    public function getIsConnectedSpotifyAttribute(): bool {
        if($this->spotify_accessToken == null || $this->spotify_refreshToken == null || $this->spotify_user_id == null || $this->spotify_lastRefreshed == null)
            return false;
        if($this->spotify_lastRefreshed->diffInMinutes() > 120)
            return false;
        //TODO: Check if Token is valid
        return true;
    }

    public function getIsConnectedTwitterAttribute(): bool {
        if($this->twitter_id == null || $this->twitter_token == null || $this->twitter_tokenSecret == null)
            return false;
        //TODO: Check if Token is valid
        return true;
    }

    public function getIsConnectedTelegramAttribute(): bool {
        return $this->telegram_id != null;
    }

}
