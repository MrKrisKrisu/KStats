<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use Notifiable, HasFactory;

    protected $fillable = ['username', 'email', 'password', 'last_login'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    public function socialProfile()
    {
        return $this->hasOne(SocialLoginProfile::class);
    }

    public function settings()
    {
        return $this->hasMany(UserSettings::class, 'user_id', 'id');
    }

    public function spotifyActivity()
    {
        return $this->hasMany(SpotifyPlayActivity::class);
    }

    public function spotifySessions()
    {
        return $this->hasMany(SpotifySession::class);
    }

    public function reweReceipts()
    {
        return $this->hasMany(ReweBon::class, 'user_id', 'id');
    }

}
