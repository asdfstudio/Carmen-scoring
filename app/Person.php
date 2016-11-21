<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
		use SoftDeletes;

		protected $table = 'people';

		protected $dates = ['deleted_at'];

		protected $fillable = ['first_name', 'last_name', 'email'];

    //
		public function subject()
		{
			return $this->morphTo();
		}

		public function getFullNameAttribute()
		{
			return $this->first_name . ' ' . $this->last_name;
		}


		public function user()
		{
			return $this->hasOne('App\User', 'person_id');
		}
}
