<?php

namespace App\Http\Controllers\Judge;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;
use App\Caption;
use App\Standing;
use App\Schedule;
use App\ScheduleItem;
use App\Judge;

use Auth;

class ScheduleController extends Controller
{
    public function index($competition_id)
    {
      $competition = Competition::withoutGlobalScope('organization')->with('schedules')->find($competition_id);

      return view('schedule.judge.index',compact('competition'));
    }


    public function show($competition_id, $schedule_id)
    {
      $competition = Competition::withoutGlobalScope('organization')->find($competition_id);
      $schedule = Schedule::find($schedule_id);

      $judge_id = Auth::user()->person_id;
      $judge = Judge::with(['divisions' => function($query) use ($competition_id) {
        $query->where('competition_id', $competition_id);
      }])->find($judge_id);

			return view('schedule.judge.show',compact('competition', 'schedule', 'judge'));
    }
}
