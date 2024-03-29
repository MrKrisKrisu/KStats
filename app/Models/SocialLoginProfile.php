<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SocialLoginProfile extends Model {

    use HasFactory;

    protected $fillable = [
        'user_id', 'telegram_id',
        'spotify_accessToken', 'spotify_refreshToken', 'spotify_lastRefreshed', 'spotify_user_id', 'spotify_scopes',
        'spotify_expires_at', 'spotify_last_fetched', 'grocy_host', 'grocy_key',
    ];
    protected $hidden   = ['spotify_accessToken', 'spotify_refreshToken'];
    protected $appends  = ['isConnectedSpotify', 'isConnectedTelegram'];
    protected $dates    = ['spotify_expires_at', 'spotify_lastRefreshed'];
    public    $casts    = [
        'grocy_key'            => 'encrypted',
        'spotify_last_fetched' => 'datetime',
    ];

    public function user(): HasOne {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getIsConnectedSpotifyAttribute(): bool {
        if($this->spotify_accessToken === null || $this->spotify_refreshToken === null || $this->spotify_user_id === null) {
            return false;
        }
        if($this->spotify_lastRefreshed === null || Carbon::parse($this->spotify_lastRefreshed)->diffInMinutes() > 120) {
            return false;
        }
        //TODO: Check if Token is valid
        return true;
    }

    public function getIsConnectedTelegramAttribute(): bool {
        return $this->telegram_id !== null;
    }
}
