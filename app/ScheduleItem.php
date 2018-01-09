<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleItem extends Model
{
		//use SoftDeletes;

		//protected $dates = ['scheduled_time'];

		protected static function boot()
    {
      parent::boot();

      static::addGlobalScope('performanceOrder', function(Builder $builder) {
					$builder->orderBy('performance_order', 'asc');
      });
    }

		protected $fillable = ['round_id', 'choir_id', 'name', 'performance_order', 'scheduled_time'];


		public function schedule()
		{
			return $this->belongsTo('App\Schedule');
		}

		public function round()
		{
			return $this->belongsTo('App\Round');
		}

		public function choir()
		{
			return $this->belongsTo('App\Choir');
		}


		public function setScheduledTimeAttribute($value)
		{
			/*if($value == '00:00:00')
			{
				$value = NULL;
			}*/

			$this->attributes['scheduled_time'] = date("G:i", strtotime($value));
		}

}
