<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReweProductCategory extends Model
{
    public function parent()
    {
        return $this->hasOne(ReweProductCategory::class, 'id', 'parent_id');
    }
}
