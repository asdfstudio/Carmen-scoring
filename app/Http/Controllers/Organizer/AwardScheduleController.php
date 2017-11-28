<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\AwardSchedule;
use App\AwardScheduleItem;
use App\Caption;
use App\AwardWinner;
use App\Standing;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class AwardScheduleController extends Controller
{


    public function index($competition_id)
    {
      $competition = Competition::with('awardSchedules')->find($competition_id);

      return view('award-schedule.organizer.index', compact('competition'));
    }


    public function create($competition_id, Request $request, FormBuilder $formBuilder)
    {
      $competition = Competition::find($competition_id);
      $form = $formBuilder->create('AwardSchedule\CreateForm', [
        'method' => 'post',
        'url' => route('organizer.competition.award-schedule.store', [$competition]),
      ]);
      return view('award-schedule.organizer.create', compact('competition', 'schedule', 'form'));
    }



    public function store(Request $request, FormBuilder $formBuilder, $competition_id)
    {
      $competition = Competition::find($competition_id);

      $form = $formBuilder->create('AwardSchedule\CreateForm', [
        'model' => false,
      ]);

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      $schedule = new AwardSchedule;
      $schedule->name = $request->input('name');
      $competition->awardSchedules()->save($schedule);

      if($request->wantsJson())
      {
        return response()->json($schedule);
      }

      return redirect()->route('organizer.competition.award-schedule.show',[$competition, $schedule])->with('success', 'Award Schedule updated.');
    }



    public function edit($competition_id, $schedule_id, FormBuilder $formBuilder)
    {
      $competition = Competition::find($competition_id);
      $schedule = AwardSchedule::find($schedule_id);
      $form = $formBuilder->create('AwardSchedule\CreateForm', [
        'model' => $schedule,
        'method' => 'post',
        'url' => route('organizer.competition.award-schedule.update', [$competition, $schedule]),
      ]);
      return view('award-schedule.organizer.edit', compact('competition', 'schedule', 'form'));
    }



    public function update(Request $request, FormBuilder $formBuilder, $competition_id, $schedule_id)
    {
      $competition = Competition::find($competition_id);
      $schedule = AwardSchedule::find($schedule_id);

      $form = $formBuilder->create('AwardSchedule\CreateForm', [
        'model' => $schedule,
      ]);

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      $schedule->name = $request->input('name');
      $schedule->save();

      if($request->wantsJson())
      {
        return response()->json($schedule);
      }

      return redirect()->route('organizer.competition.award-schedule.show',[$competition, $schedule])->with('success', 'Award Schedule updated.');
    }



    public function show($competition_id, $schedule_id, FormBuilder $formBuilder)
    {
      $competition = Competition::find($competition_id);
      $schedule = AwardSchedule::with(['items' => function($query) {
        $query->performanceOrder();
      }, 'items.division', 'items.award' => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'items.caption'])->find($schedule_id);

      //$divisions = $competition->divisions()->with(['awards', 'awards.winner'])->get();
      //dd($divisions);

      $awardWinners = AwardWinner::whereHas('division', function($query) use ($competition_id) {
        $query->where('competition_id', $competition_id);
      })->with(['choir'])->get();


      $standings = Standing::whereHas('division', function($query) use ($competition_id) {
        $query->where('competition_id', $competition_id);
      })->with(['choirs'])->get();

      //dd($standings);

      //dd($schedule);

      $deleteForm = $formBuilder->create('GenericDeleteForm', [
        'url' => route('organizer.competition.award-schedule.destroy',[$competition, $schedule])
      ]);

      return view('award-schedule.organizer.show', compact('competition', 'schedule', 'deleteForm', 'awardWinners', 'standings'));
    }



    public function builder($competition_id, $schedule_id, FormBuilder $formBuilder)
    {
      $competition = Competition::with(['divisions', 'divisions.awards' => function($query) {
        $query->withoutGlobalScope('organization');
      }])->find($competition_id);

      $schedule = AwardSchedule::with(['items' => function($query) {
        $query->performanceOrder();
      }, 'items.division', 'items.award' => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'items.caption'])->find($schedule_id);

      $captions = Caption::all();

      $excludedScheduleItems = AwardScheduleItem::whereHas('schedule', function($query) use ($competition_id) {
        $query->where('competition_id', $competition_id);
      })->get();

      return view('award-schedule.organizer.builder', compact('competition', 'schedule', 'captions', 'excludedScheduleItems'));
    }


    public function builderStore($competition_id, $schedule_id, FormBuilder $formBuilder, Request $request)
    {
      $competition = Competition::find($competition_id);
      $schedule = AwardSchedule::find($schedule_id);

      $items = $request->input('items');

      $scheduleItems = [];

      foreach($items as $item)
      {
        $scheduleItems[] = new AwardScheduleItem($item);
      }

      $deleted = $schedule->items()->delete();
      $success = $schedule->items()->saveMany($scheduleItems);

      if($request->wantsJson())
      {
        return response()->json($schedule);
      }
    }



    public function destroy(Request $request, $competition_id, $schedule_id)
    {
      $competition = Competition::find($schedule_id);
      $schedule = AwardSchedule::find($schedule_id);

      $schedule->delete();

      if($request->wantsJson())
      {
        return response()->json($schedule);
      }

			return redirect()->route('organizer.competition.award-schedule.index', $competition);
    }
}
