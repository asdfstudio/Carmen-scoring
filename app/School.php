<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;
		
		protected $dates = ['deleted_at'];
		
		protected $fillable = ['name'];
		
		
		public function choirs()
		{
			return $this->hasMany('App\Choir');
		}
		
		public function place()
		{
			return $this->morphOne('App\Place','subject');
		}
}
