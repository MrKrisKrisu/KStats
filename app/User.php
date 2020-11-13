<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use Notifiable, HasFactory;

    protected $fillable = ['username', 'email', 'password', 'last_login', 'privacy_confirmed_at'];
    protected $hidden   = ['password', 'remember_token'];
    protected $dates    = ['privacy_confirmed_at', 'last_login', 'email_verified_at'];

    public function socialProfile()
    {
        if ($this->hasOne(SocialLoginProfile::class)->count() == 0)
            SocialLoginProfile::create(['user_id' => $this->id]);
        return $this->hasOne(SocialLoginProfile::class);
    }

    public function settings()
    {
        return $this->hasMany(UserSettings::class, 'user_id', 'id');
    }

    public function spotifyActivity()
    {
        return $this->hasMany(SpotifyPlayActivity::class, 'user_id', 'id');
    }

    public function spotifyRatedTracks()
    {
        return $this->hasMany(SpotifyTrackRating::class, 'user_id', 'id');
    }

    public function spotifySessions()
    {
        return $this->hasMany(SpotifySession::class, 'user_id', 'id');
    }

    public function reweReceipts()
    {
        return $this->hasMany(ReweBon::class, 'user_id', 'id')->orderBy('timestamp_bon');
    }

}
