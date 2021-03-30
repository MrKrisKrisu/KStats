<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwitterUnfollower extends Model {

    protected $fillable = ['unfollower_id', 'account_id', 'unfollowed_at'];
    protected $dates    = ['unfollowed_at'];

    public function twitter_profile(): BelongsTo {
        return $this->belongsTo(TwitterProfile::class, 'account_id', 'id');
    }

    public function unfollower_profile(): BelongsTo {
        return $this->belongsTo(TwitterProfile::class, 'unfollower_id', 'id');
    }
}
