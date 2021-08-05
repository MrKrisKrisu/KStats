<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwitterDaily extends Model {

    use HasFactory;

    protected $fillable = ['profile_id', 'date', 'follower_count', 'friends_count', 'statuses_count'];
    protected $dates    = ['date'];

    public function profile(): BelongsTo {
        return $this->belongsTo(TwitterProfile::class, 'profile_id', 'id');
    }
}
