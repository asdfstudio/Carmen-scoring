<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

		public function getIsJudgeAttribute()
		{
			return $this->person_type == 'App\Judge' ? true : false;
		}

		public function getIsJudgeTextAttribute()
		{
			return $this->getIsJudgeAttribute() ? 'Judge' : false;
		}

		public function user()
		{
			return $this->hasOne('App\User', 'person_id');
		}


}
