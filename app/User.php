<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable {

    use Notifiable, HasFactory;

    protected $fillable = ['username', 'email', 'password', 'visible', 'last_login', 'locale', 'privacy_confirmed_at'];
    protected $hidden   = ['password', 'remember_token'];
    protected $dates    = ['privacy_confirmed_at', 'last_login', 'email_verified_at'];
    protected $casts    = ['visible' => 'boolean'];

    public function socialProfile(): HasOne {
        if($this->hasOne(SocialLoginProfile::class)->count() == 0)
            SocialLoginProfile::create(['user_id' => $this->id]);
        return $this->hasOne(SocialLoginProfile::class);
    }

    public function settings(): HasMany {
        return $this->hasMany(UserSettings::class, 'user_id', 'id');
    }

    public function spotifyActivity(): HasMany {
        return $this->hasMany(SpotifyPlayActivity::class, 'user_id', 'id');
    }

    public function spotifyRatedTracks(): HasMany {
        return $this->hasMany(SpotifyTrackRating::class, 'user_id', 'id');
    }

    public function spotifyLikedTracks(): HasMany {
        return $this->hasMany(SpotifyTrackRating::class, 'user_id', 'id')->where('rating', '1');
    }

    public function spotifySessions(): HasMany {
        return $this->hasMany(SpotifySession::class, 'user_id', 'id');
    }

    public function friends(): BelongsToMany {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id');
    }

    public function reweReceipts(): HasMany {
        return $this->hasMany(ReweBon::class, 'user_id', 'id')->orderBy('timestamp_bon');
    }

}
