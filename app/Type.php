<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    /**
     * The people that belong to the role.
     */
    public function people()
    {
        return $this->belongsToMany('App\Person', 'type_id', 'person_id', 'person_type');
    }
}
