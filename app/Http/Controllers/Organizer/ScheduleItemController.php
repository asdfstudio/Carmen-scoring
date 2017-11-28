<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Schedule;
use App\ScheduleItem;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class ScheduleItemController extends Controller
{


    public function store(Request $request, FormBuilder $formBuilder, $competition_id, $schedule_id)
    {
      $competition = Competition::find($competition_id);
      $schedule = Schedule::find($schedule_id);

      $item = new ScheduleItem;
      $item->scheduled_time = $request->input('scheduled_time');
      $item->round_id = 89;
      $item->choir_id = 1;

      $schedule->items()->save($item);

      if($request->wantsJson())
      {
        return response()->json($item);
      }
    }

    public function update(Request $request, FormBuilder $formBuilder, $competition_id, $schedule_id, $item_id)
    {
      $competition = Competition::find($competition_id);
      $schedule = Schedule::find($schedule_id);
      $item = ScheduleItem::find($item_id);

      $item->round_id = 89;
      $item->choir_id = 1;
      $item->save();

      if($request->wantsJson())
      {
        return response()->json($item);
      }
    }


    public function destroy(Request $request, $competition_id, $schedule_id, $item_id)
    {
      $competition = Competition::find($schedule_id);
      $schedule = Schedule::find($schedule_id);
			$item = ScheduleItem::find($item_id);

      $item->delete();

      if($request->wantsJson())
      {
        return response()->json($item);
      }
    }
}
