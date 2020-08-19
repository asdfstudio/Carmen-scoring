<?php

namespace App;

use App\Scopes\OrderByNameScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Choir extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['school_id','name'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByNameScope);
    }


		public function school()
		{
			return $this->belongsTo('App\School');
		}

/*
		public function directors()
		{
			return $this->morphMany('App\Director','subject');
		}
*/

		public function directors()
		{
			return $this->belongsToMany('App\Director');
		}

/*
		public function choreographers()
		{
			return $this->morphMany('App\Choreographer','subject');
		}
*/

		public function choreographers()
		{
			return $this->belongsToMany('App\Choreographer');
		}

    public function performers()
    {
      return $this->hasMany('App\Performer');
    }

		public function divisions()
		{
			//return $this->hasMany('App\Division');
			return $this->belongsToMany('App\Division');
		}

    public function scheduleItems()
    {
        return $this->hasMany('App\ScheduleItem');
    }

    public function rounds()
    {
      return $this->belongsToMany('App\Round')->withPivot('performance_order');
    }

    public function performance_order($round)
    {
      $round_id = is_object($round) && is_a($round, 'App\Round') ? $round->id : $round;
      $round = $this->rounds->where('id', $round_id);
      return count($round) ? $round->pivot->performance_order : null;
    }

    // This doesn't actually work because the Division relationship is many-to-many.
    /*
		public function competitions()
		{
			return $this->hasManyThrough('App\Competition','App\Division');
		}
    */
    
    public function penalties()
		{
			return $this->belongsToMany('App\Penalty');
		}

    public function comments()
		{
			//return $this->belongsToMany('App\Comment');
			return $this->morphMany('App\Comment', 'recipient');
			//return $this->hasMany('App\Comment');
		}

    public function standings()
		{
			return $this->belongsToMany('App\Standing')->withPivot('raw_rank', 'final_rank')->orderBy('pivot_final_rank', 'ASC');
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

    public function recordings()
	{
		return $this->hasMany('App\Recording');
	}

}
