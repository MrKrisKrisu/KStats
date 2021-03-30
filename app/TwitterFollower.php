<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwitterFollower extends Model {

    protected $fillable = ['follower_id', 'followed_id', 'updated_at'];

    public function follower(): BelongsTo {
        return $this->belongsTo(TwitterProfile::class, 'follower_id', 'id');
    }

    public function followed(): BelongsTo {
        return $this->belongsTo(TwitterProfile::class, 'followed_id', 'id');
    }
}
