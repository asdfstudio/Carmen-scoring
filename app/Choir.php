<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Choir extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['school_id','name'];


		public function school()
		{
			return $this->belongsTo('App\School');
		}


		public function directors()
		{
			return $this->morphMany('App\Director','subject');
		}


		public function choreographers()
		{
			return $this->morphMany('App\Choreographer','subject');
		}


		public function divisions()
		{
			return $this->hasMany('App\Division');
		}

    public function rounds()
    {
      return $this->belongsToMany('App\Round');
    }


		public function competitions()
		{
			return $this->hasManyThrough('App\Competition','App\Division');
		}

    public function penalties()
		{
			return $this->belongsToMany('App\Penalty');
		}


    public function name()
    {
      return '"'.$this->name.'"';
    }

    public function getFullNameAttribute()
    {
      $h = '';

      if($this->school)
      {
        $h.= $this->school->name . ' ';
      }
      $h.= $this->name();

      return $h;
    }

}
