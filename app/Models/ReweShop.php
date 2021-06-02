<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReweShop extends Model {

    protected $fillable = [
        'id', 'name', 'address', 'zip', 'city', 'phone', 'opening_hours'
    ];
}
