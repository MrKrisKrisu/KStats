<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEmail extends Model {
    protected $fillable = [
        'email', 'verified_user_id', 'unverified_user_id', 'verification_key'
    ];

    public function unverifiedUser(): BelongsTo {
        return $this->belongsTo(User::class, 'unverified_user_id', 'id');
    }

    public function verifiedUser(): BelongsTo {
        return $this->belongsTo(User::class, 'verified_user_id', 'id');
    }
}
