<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotifyDeviceActivity extends Model
{
    public function device()
    {
        return $this->belongsTo(SpotifyDevice::class, 'device_id', 'device_id');
    }
}
