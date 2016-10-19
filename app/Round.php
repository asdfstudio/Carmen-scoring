<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Round extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['division_id','name'];


		public function division()
		{
			return $this->belongsTo('App\Division');
		}


		public function isScoringActive()
		{
			return $this->is_scoring_active ? 'Active' : 'Not Active';
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

}
