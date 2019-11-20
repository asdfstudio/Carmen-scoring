<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Judge extends Person
{
		protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('judge', function(Builder $builder) {
          //$builder->where('person_type', '=', 'App\Judge');
          $builder->join('person_type', 'people.id', '=', 'person_type.person_id')->where('type_id', '=', 1);
        });

				static::addGlobalScope('orderByLastName', function(Builder $builder) {
					$builder->orderBy('last_name', 'ASC');
				});

				static::created(function ($model)
        {
          $model->types()->syncWithoutDetaching([1]);
        });
    }


    public function types()
    {
        return $this->belongsToMany('App\Type', 'person_type', 'person_id', 'type_id');
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
    public function recordings()
		{
			return $this->hasMany('App\Recording');
		}

}
