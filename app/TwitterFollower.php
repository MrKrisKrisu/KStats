<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwitterFollower extends Model
{
    protected $fillable = ['follower_id', 'followed_id', 'updated_at'];

    public function follower()
    {
        return $this->belongsTo(TwitterProfile::class, 'follower_id', 'id');
    }

    public function followed()
    {
        return $this->belongsTo(TwitterProfile::class, 'followed_id', 'id');
    }
}
