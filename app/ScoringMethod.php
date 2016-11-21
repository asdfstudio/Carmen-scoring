<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScoringMethod extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['name'];

		public function divisions()
		{
			return $this->hasMany('App\Division');
		}

    public function getSlugAttribute()
    {
      return strtolower($this->name);
    }
}
