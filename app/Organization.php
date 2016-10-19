<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;
		
		protected $dates = ['deleted_at'];
		
		protected $fillable = ['name'];
		
		public function people()
		{
			return $this->morphMany('App\Person','subject');
		}
		
		
		public function place()
		{
			return $this->morphOne('App\Place','subject');
		}
		
		
		public function competitions()
		{
			return $this->hasMany('App\Competition');
		}
		
		public function users()
		{
			return $this->hasMany('App\User');
		}
}
