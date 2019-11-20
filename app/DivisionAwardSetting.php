<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisionAwardSetting extends Model
{
    protected $fillable = ['division_id', 'caption_id', 'award_count', 'award_sponsors'];

    protected $casts = [
      'award_sponsors' => 'array'
    ];

    public function division()
		{
			return $this->belongsTo('App\Division');
		}

    public function caption()
		{
			return $this->belongsTo('App\Caption');
		}

    public function awardSponsor($rank = false)
    {
      if (!$rank) return false;

      $index = $rank - 1;

      if (!empty($this->award_sponsors_array[$index])) {
        return $this->award_sponsors_array[$index];
      }

      return false;
    }


    public function getAwardSponsorsArrayAttribute()
    {
      return explode(PHP_EOL, $this->award_sponsors);
    }

    /*public function getNamedRankAttribute()
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
		}*/
}
