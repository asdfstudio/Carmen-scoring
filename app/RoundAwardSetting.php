<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoundAwardSetting extends Model
{
    protected $fillable = ['round_id', 'caption_id', 'award_count', 'award_sponsors'];

    protected $casts = [
      'award_sponsors' => 'array'
    ];

    public function round()
		{
			return $this->belongsTo('App\Round');
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
}
