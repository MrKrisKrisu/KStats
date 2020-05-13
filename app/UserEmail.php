<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model
{
    protected $fillable = [
        'email', 'verified_user_id', 'unverified_user_id', 'verification_key'
    ];
}
