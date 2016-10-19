<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sheet extends Model
{
    use SoftDeletes;
		
		protected $dates = ['deleted_at'];
		
		protected $fillable = ['name'];
		
		
		public function divisions()
		{
			return $this->hasMany('App\Division'); 
		}
		
		public function criteria()
    {
        return $this->belongsToMany('App\Criterion');
    }
}
