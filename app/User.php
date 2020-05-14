<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function socialProfile()
    {
        return $this->hasOne(SocialLoginProfile::class);
    }

    public function settings()
    {
        return $this->hasMany('App\UserSettings', 'user_id', 'id');
    }

    /* SPOTIFY */

    public function spotifyActivity()
    {
        return $this->hasMany(SpotifyPlayActivity::class);
    }

    public function spotifySessions()
    {
        return $this->hasMany(SpotifySession::class);
    }

    public function reweReceipts() {
        return $this->hasMany(ReweBon::class, 'user_id', 'id');
    }

}
