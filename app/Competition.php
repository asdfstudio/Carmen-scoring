<?php

namespace App;

use App\Scopes\OrderByNameScope;
use App\User;
use Auth;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competition extends Model
{
    use SoftDeletes;

    use RestrictsOrganization;

		protected $dates = ['deleted_at', 'begin_date', 'end_date'];

		protected $fillable = ['organization_id', 'name', 'slug', 'access_code', 'dates', 'use_runner_up_names',  'is_archived', 'begin_date', 'end_date'];

    /*protected $casts = [
      'begin_date' => 'date',
      'end_date' => 'date'
    ];*/

    protected $casts = [
      'use_runner_up_names' => 'array'
    ];
    
		protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope(new OrderByNameScope);
    }

    public function getBeginDateAttribute($value)
    {
      return $value;
    }

    public function getEndDateAttribute($value)
    {
      return $value;
    }


		public function scopeActive($query)
		{
			return $query->whereNull('is_archived');
		}

    public function scopeYear($query, $year)
		{
			return $query->whereYear('begin_date', '=',$year);
		}


		public function scopeArchived($query)
		{
			return $query->whereNotNull('is_archived');
		}

    public function scopeCompleted($query)
		{
			return $query->where('is_completed', 1);
		}



		public function organization()
		{
			return $this->belongsTo('App\Organization');
		}


		public function place()
		{
			return $this->morphOne('App\Place','subject');
		}


    public function schedules()
		{
			return $this->hasMany('App\Schedule');
		}

    public function awardSchedules()
		{
			return $this->hasMany('App\AwardSchedule');
		}

		public function divisions()
		{
			return $this->hasMany('App\Division');
		}

    public function soloDivisions()
		{
			return $this->hasMany('App\SoloDivision');
		}

    public function rounds()
		{
			return $this->hasManyThrough('App\Round','App\Division');
		}

    public function commentUrls()
    {
      return $this->hasMany('App\CommentUrl');
    }



    public function status()
    {
      if($this->is_archived)
			{
				return 'Archived';
			}
      elseif($this->is_completed)
			{
				return 'Completed';
			}
			else
			{
				return 'Active';
			}
    }

    public function status_slug()
		{
      if($this->is_archived)
			{
				return 'archived';
			}
      elseif($this->is_completed)
			{
				return 'completed';
			}
			else
			{
				return 'active';
			}
		}

    public function getStatusAttribute()
    {
      return $this->status();
    }

    public function getStatusSlugAttribute()
    {
      return $this->status_slug();
    }


    public function status_label($class_attr = false)
    {
      $class_array = ['label', 'status', $this->status_slug];

      if($class_attr)
        $class_array[] = $class_attr;

      $class = implode(' ', $class_array);

      return '<span class="'.$class.'">'.$this->status.'</span>';
    }

    public function setSlugAttribute($value)
    {
      if($value == false)
      {
        $this->attributes['slug'] = str_slug($this->name);
      }
      else {
        $this->attributes['slug'] = str_slug($value);
      }
    }

    public function getResultsUrlAttribute()
    {
      if($this->slug == false) return false;

      return route('results.competition.show-custom', $this->slug);
    }


    public function setAccessCodeAttribute($value)
    {
      $this->attributes['access_code'] = strtolower($value);
    }


    public function activate()
    {
      $this->is_completed = false;
      $this->is_archived = NULL;
      return $this->save();
    }

    public function complete()
    {
      $this->is_completed = true;
      $this->is_archived = NULL;
      return $this->save();
    }

    public function archive()
    {
      $this->is_completed = true;
      $this->is_archived = true;
      return $this->save();
    }
}
