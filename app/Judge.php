<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Judge extends Person
{
		protected static function boot()
    {
        parent::boot();

        //static::addGlobalScope('judge', function(Builder $builder) {
        //    $builder->where('person_type', '=', 'App\Judge');
        //});

				static::addGlobalScope('orderByLastName', function(Builder $builder) {
					$builder->orderBy('last_name', 'ASC');
				});

				static::saving(function ($model)
        {
            $model->attributes['person_type'] = get_class($model);
        });
    }


		public function divisions()
    {
        return $this->belongsToMany('App\Division')->withPivot('caption_id');
    }


		public function captions()
    {
        return $this->belongsToMany('App\Caption','division_judge')->withPivot('division_id');
    }

		public function comments()
		{
			return $this->hasMany('App\Comment');
		}

}
