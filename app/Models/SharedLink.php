<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SharedLink extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'share_id', 'spotify_tracks', 'spotify_days'];
    protected $casts    = [
        'user_id'        => 'integer',
        'share_id'       => 'string',
        'spotify_tracks' => 'integer',
        'spotify_days'   => 'integer'
    ];
    protected $appends  = ['url'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getUrlAttribute(): string {
        return route('public.show', [
            'username' => auth()->user()->username,
            'shareId'  => $this->share_id,
        ]);
    }
}
