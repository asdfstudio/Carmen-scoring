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


		public function getNamedRankAttribute()
		{
			if(!$this->rank) return false;
			
			if ($this->division->competition->use_runner_up_names) {
				if ($this->rank == 1) {
          $rank_name = 'Grand Champion';
				} else {
          $rank_name = ordinal($this->rank - 1) . ' Runner Up';
        }
      } else {
        $rank_name = ordinal($this->rank);
      }

			return $rank_name;
		}


		public function scopePerformanceOrder($query)
		{
			return $query->orderBy('performance_order', 'asc');
		}

}
