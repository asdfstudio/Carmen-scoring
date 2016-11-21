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

		protected $fillable = ['organization_id','name','is_archived'];


		protected static function boot()
    {
        parent::boot();

        /*static::addGlobalScope('active', function(Builder $builder) {
            $builder->whereNull('is_archived');
        });*/
    }


		public function scopeActive($query)
		{
			return $query->whereNull('is_archived');
		}


		public function scopeArchived($query)
		{
			return $query->whereNotNull('is_archived');
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
      if($this->is_completed)
			{
				return 'Completed';
			}
			elseif($this->is_scoring_active)
			{
				return 'Active';
			}
			else
			{
				return 'Inactive';
			}
    }

    public function status_slug()
		{
			if($this->is_completed)
			{
				return 'completed';
			}
			elseif($this->is_scoring_active)
			{
				return 'active';
			}
			else
			{
				return 'inactive';
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
}
