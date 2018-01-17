<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawScore extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['division_id', 'round_id', 'choir_id', 'judge_id', 'criterion_id', 'score'];


		public function division()
    {
        return $this->belongsTo('App\Division');
    }

		public function choir()
    {
        return $this->belongsTo('App\Choir');
    }

		public function judge()
    {
        return $this->belongsTo('App\Judge');
    }

		public function round()
    {
        return $this->belongsTo('App\Round');
    }

		public function criterion()
    {
        return $this->belongsTo('App\Criterion');
    }


		public function getScoreAttribute($value)
		{
			return number_format($value, 1);
		}

}
