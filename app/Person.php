<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Judge;
use App\Director;
use App\Choreographer;

class Person extends Model
{
		use SoftDeletes;

		protected $table = 'people';

		protected $dates = ['deleted_at'];

		protected $fillable = ['first_name', 'last_name', 'email', 'tel'];

    //
		public function subject()
		{
			return $this->morphTo();
		}


		public function setTelAttribute($value)
		{
			// strip non-numbers from string and add US code to front
			if($value)
			{
				$this->attributes['tel'] = "+1" . preg_replace("/[^0-9]/", "", $value);
			}
		}

		public function getTelAttribute($value)
		{
			if($value == false) return $value;

			// Strip US code to front
			$value = str_replace("+1", "", $value);

			return "(".substr($value, 0, 3).") ".substr($value, 3, 3)."-".substr($value,6);
		}

		public function getFullNameAttribute()
		{
			return $this->first_name . ' ' . $this->last_name;
		}

    /**
     * The types that belong to the person (App\Judge, App\Director, App\Choreographer).
     */
    public function types()
    {
        return $this->belongsToMany('App\Type');
    }

		public function getIsTypeAttribute($type)
		{
      return $this->types->contains('name', $type);
		}

		public function getIsJudgeAttribute()
		{
      // Changed relationship to "type" (Judge, Director, Choreographer) to be many-to-many.
			//return $this->person_type == 'App\Judge' ? true : false;
      return $this->getIsTypeAttribute('App\Judge');
		}

		public function getIsJudgeTextAttribute()
		{
			return $this->getIsJudgeAttribute() ? 'Judge' : false;
		}

		public function judge()
		{
      return Judge::with('divisions', 'captions', 'comments')->find($this->id);
		}

		public function getIsDirectorAttribute()
		{
      return $this->getIsTypeAttribute('App\Director');
		}

		public function getIsDirectorTextAttribute()
		{
			return $this->getIsDirectorAttribute() ? 'Director' : false;
		}

		public function director()
		{
      return Director::with('choirs')->find($this->id);
		}

		public function getIsChoreographerAttribute()
		{
      return $this->getIsTypeAttribute('App\Choreographer');
		}

		public function getIsChoreographerTextAttribute()
		{
			return $this->getIsChoreographerAttribute() ? 'Choreographer' : false;
		}

		public function choreographer()
		{
      return Choreographer::with('choirs')->find($this->id);
		}

		public function user()
		{
			return $this->hasOne('App\User', 'person_id');
		}

}
