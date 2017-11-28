<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AwardSchedule extends Model
{
		use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['name'];

		public function competitions()
		{
			return $this->belongsTo('App\Competition');
		}

    public function items()
    {
        return $this->hasMany('App\AwardScheduleItem');
    }

}
