<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meter extends Model {

    use HasFactory;

    protected $fillable = ['uuid', 'user_id', 'name', 'keyword'];
    protected $casts    = [
        'uuid'    => 'string',
        'user_id' => 'integer',
        'name'    => 'string',
        'keyword' => 'string',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function readings(): HasMany {
        return $this->hasMany(MeterReading::class, 'meter_id', 'id')->orderBy('created_at');
    }
}
