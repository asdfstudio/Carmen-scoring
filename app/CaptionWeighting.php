<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaptionWeighting extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['name'];

		public function divisions()
		{
			return $this->hasMany('App\Division');
		}


    public function getFullNameAttribute()
    {
      if($this->id == 1)
        return '60% Music / 40% Show';
      elseif($this->id == 2)
        return '50% Music / 50% Show';
      else
        return false;
    }

    public function getSlugAttribute()
    {
      if($this->id == 1)
        return '60-40';
      elseif($this->id == 2)
        return '50-50';
      else
        return false;
    }
}
