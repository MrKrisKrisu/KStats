<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeterReading extends Model {

    use HasFactory;

    protected $fillable = ['meter_id', 'reading_date', 'value'];
    protected $casts    = [
        'meter_id'     => 'integer',
        'reading_date' => 'date',
        'value'        => 'float'
    ];

    public function meter(): BelongsTo {
        return $this->belongsTo(Meter::class, 'meter_id', 'id');
    }
}
