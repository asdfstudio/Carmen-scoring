<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['name','caption_weighting_id','scoring_method_id','sheet_id'];

		/*public function organization()
		{
			// There is no belongsToThrough method
		}*/

		public function competition()
		{
			return $this->belongsTo('App\Competition');
		}


		public function judges()
    {
        return $this->belongsToMany('App\Judge')->withPivot('caption_id');
    }

		public function choirs()
    {
        return $this->belongsToMany('App\Choir');
    }


		public function sheet()
		{
			return $this->belongsTo('App\Sheet');
		}


		public function scoringMethod()
		{
			return $this->belongsTo('App\ScoringMethod');
		}

		public function captionWeighting()
		{
			return $this->belongsTo('App\CaptionWeighting');
		}


		public function penalties()
    {
        return $this->belongsToMany('App\Penalty');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Award', 'division_award')->withPivot( 'choir_id', 'recipient');
    }

		public function rounds()
    {
        return $this->hasMany('App\Round');
    }


    public function status()
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
}
