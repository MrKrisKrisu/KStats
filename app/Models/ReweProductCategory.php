<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReweProductCategory extends Model {

    public function parent(): HasOne {
        return $this->hasOne(ReweProductCategory::class, 'id', 'parent_id');
    }
}
