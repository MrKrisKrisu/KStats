<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReweShop extends Model
{

    protected $fillable = [
        'id', 'name', 'address', 'zip', 'city', 'phone', 'opening_hours'
    ];
}
