<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FriendshipRequest extends Model {

    use HasFactory;

    protected $fillable = ['requester_id', 'user_id'];

    public function requester(): BelongsTo {
        return $this->belongsTo(User::class, 'requester_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
