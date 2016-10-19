<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Director extends Person
{				
		protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('director', function(Builder $builder) {
            $builder->where('person_type', '=', 'App\Director');
        });
				
				static::saving(function ($model)
        {
            $model->attributes['person_type'] = get_class($model);
        });
    }
		
}
