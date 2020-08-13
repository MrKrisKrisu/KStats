<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model
{
    protected $fillable = [
        'email', 'verified_user_id', 'unverified_user_id', 'verification_key'
    ];

    public function unverifiedUser()
    {
        return $this->belongsTo(User::class, 'unverified_user_id', 'id');
    }

    public function verifiedUser()
    {
        return $this->belongsTo(User::class, 'verified_user_id', 'id');
    }
}
