<?php

namespace App;

use App\Scopes\OrderByNameScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['name'];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByNameScope);
    }


		public function choirs()
		{
			return $this->hasMany('App\Choir');
		}

		public function place()
		{
			return $this->morphOne('App\Place','subject');
		}
}
