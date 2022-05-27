<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model {

    use HasFactory;

    protected $fillable = ['name', 'wikidata_id', 'primary_color', 'vector_logo'];
    protected $casts    = [
        'name'        => 'string',
        'wikidata_id' => 'string',
        'vector_logo' => 'string',
    ];

}
