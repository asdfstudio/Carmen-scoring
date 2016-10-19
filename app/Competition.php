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
}
