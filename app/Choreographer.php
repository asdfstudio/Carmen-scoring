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
          //$builder->where('person_type', '=', 'App\Choreographer');
          $builder->join('person_type', 'people.id', '=', 'person_type.person_id')->where('type_id', '=', 3);
        });
				
				static::created(function ($model)
        {
            $model->types()->syncWithoutDetaching([3]);
        });
    }


    public function types()
    {
        return $this->belongsToMany('App\Type', 'person_type', 'person_id', 'type_id');
    }
  
		public function choirs()
		{
			return $this->belongsToMany('App\Choir');
		}


}
