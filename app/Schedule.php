<?php

namespace App;

use App\ScheduleItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Schedule extends Model
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
        return $this->hasMany('App\ScheduleItem');
    }

		public function syncItems($items = [])
		{
			$scheduleItems = [];

      foreach($items as $item)
      {
        $scheduleItems[] = new ScheduleItem($item);
      }

      $deleted = $this->items()->delete();
      $success = $this->items()->saveMany($scheduleItems);

			return $this;
		}

}
