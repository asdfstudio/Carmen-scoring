<?php

namespace App;

use App\AwardScheduleItem;
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

		public function syncItems($items = [])
		{
			$scheduleItems = [];

      foreach($items as $item)
      {
        $scheduleItems[] = new AwardScheduleItem($item);
      }

      $deleted = $this->items()->delete();
      $success = $this->items()->saveMany($scheduleItems);

			return $this;
		}

}
