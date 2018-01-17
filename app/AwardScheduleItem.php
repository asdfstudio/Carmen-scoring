<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AwardScheduleItem extends Model
{
		protected $fillable = ['division_id', 'round_id', 'award_id', 'caption_id', 'rank', 'performance_order'];


		public function schedule()
		{
			return $this->belongsTo('App\AwardSchedule', 'award_schedule_id');
		}

		public function division()
		{
			return $this->belongsTo('App\Division');
		}

		public function round()
		{
			return $this->belongsTo('App\Round');
		}

		public function award()
		{
			return $this->belongsTo('App\Award');
		}

		/*public function divisionAward()
		{
			return $this->hasManyThrough('App\Award', 'App\Division');
		}*/

		public function caption()
		{
			return $this->belongsTo('App\Caption');
		}


		public function scopePerformanceOrder($query)
		{
			return $query->orderBy('performance_order', 'asc');
		}

}
