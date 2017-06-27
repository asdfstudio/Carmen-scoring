<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Builder;

class Competition extends Model
{
    use SoftDeletes;

    use RestrictsOrganization;

		protected $dates = ['deleted_at'];

		protected $fillable = ['organization_id', 'name', 'slug', 'access_code', 'dates', 'use_runner_up_names' ,'is_archived'];


		protected static function boot()
    {
        parent::boot();
    }


		public function scopeActive($query)
		{
			return $query->whereNull('is_archived');
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


		public function divisions()
		{
			return $this->hasMany('App\Division');
		}

    public function rounds()
		{
			return $this->hasManyThrough('App\Round','App\Division');
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

      $class = implode($class_array,' ');

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
