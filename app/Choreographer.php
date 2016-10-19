<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Choreographer extends Person
{				
		protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('choreographer', function(Builder $builder) {
            $builder->where('person_type', '=', 'App\Choreographer');
        });
				
				static::saving(function ($model)
        {
            $model->attributes['person_type'] = get_class($model);
        });
    }
		
}
