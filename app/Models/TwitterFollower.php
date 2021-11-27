<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwitterFollower extends Model {

    protected $fillable = ['follower_id', 'followed_id', 'last_checked'];
    protected $casts    = [
        'follower_id'  => 'integer',
        'followed_id'  => 'integer',
        'last_checked' => 'datetime',
    ];

    public function follower(): BelongsTo {
        return $this->belongsTo(TwitterProfile::class, 'follower_id', 'id');
    }

    public function followed(): BelongsTo {
        return $this->belongsTo(TwitterProfile::class, 'followed_id', 'id');
    }
}
