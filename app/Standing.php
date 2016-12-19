<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    protected $fillable = ['division_id', 'round_id', 'caption_id', 'is_consensus_scoring'];

    protected $table = 'standings';

    public function division()
		{
			return $this->belongsTo('App\Division');
		}

    public function round()
		{
			return $this->belongsTo('App\Round');
		}

    public function caption()
		{
			return $this->belongsTo('App\Caption');
		}

    public function choirs()
		{
			return $this->belongsToMany('App\Choir')->withPivot('raw_rank', 'final_rank')->orderBy('pivot_final_rank', 'ASC');
		}


    public function getCaptionSlugAttribute()
    {
      if($this->caption_id ==  NULL) return 'overall';

      return $this->caption->slug();
    }
}
