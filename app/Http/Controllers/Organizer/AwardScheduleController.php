<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\AwardSchedule;
use App\AwardScheduleItem;
use App\Carmen\Ratings;
use App\Caption;
use App\AwardWinner;
use App\ContestAwardWinner;
use App\RoundAwardWinner;
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
      return view('award-schedule.organizer.create', compact('competition', 'form'));
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
      }, 'items.division', 'items.division', 'items.division.awardSettings', 'items.division.choirs', 'items.round', 'items.round.competition', 'items.award' => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'items.caption'])->find($schedule_id);

      // TODO: check receives_rankings
      $awardWinners = AwardWinner::with(['division', 'division.choirs' => function ($query) {
        $query->where('choir_division.receives_rankings', 1);
      }, 'division.round' => function($query) use ($competition_id) {
          $query->where('competition_id', $competition_id);
      }])->get();

      $roundAwardWinners = RoundAwardWinner::with('round')->get();

      $contestAwardWinners = ContestAwardWinner::with(['competition' => function ($query) use ($competition_id) {
        $query->where('id', $competition_id);
      }])->get();

      $standings = Standing::whereHas('round', function($query) use ($competition_id) {
        $query->where('competition_id', $competition_id);
      })->with(['division', 'division.choirs'])->get();


      $deleteForm = $formBuilder->create('GenericDeleteForm', [
        'url' => route('organizer.competition.award-schedule.destroy',[$competition, $schedule])
      ]);

      return view('award-schedule.organizer.show', compact('competition', 'schedule', 'deleteForm', 'awardWinners', 'roundAwardWinners', 'contestAwardWinners', 'standings'));
    }



    // TODO: If the majority of these are the same query as the "show" view, combine them and change the route to use different layouts, create an easily
    // digestible scope, or something to reduce cutting and pasting here.
    public function showAsAnnouncer($competition_id, $schedule_id)
    {
      $competition = Competition::find($competition_id);
      $schedule = AwardSchedule::with(['items' => function($query) {
        $query->performanceOrder();
      }, 'items.division', 'items.division', 'items.division.awardSettings', 'items.round', 'items.round.competition', 'items.award' => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'items.caption'])->find($schedule_id);

      $awardWinners = AwardWinner::with(['division', 'division.choirs', 'division.round' => function($query) use ($competition_id) {
          $query->where('competition_id', $competition_id);
      }])->get();

      $roundAwardWinners = RoundAwardWinner::with('round')->get();

      $contestAwardWinners = ContestAwardWinner::with(['competition' => function ($query) use ($competition_id) {
        $query->where('id', $competition_id);
      }])->get();

      $standings = Standing::whereHas('round', function($query) use ($competition_id) {
        $query->where('competition_id', $competition_id);
      })->with(['division', 'division.choirs'])->get();

      return view('award-schedule.organizer.show-announcer', compact('competition', 'schedule', 'awardWinners', 'roundAwardWinners', 'contestAwardWinners', 'standings'));
    }



    public function builder($competition_id, $schedule_id, FormBuilder $formBuilder)
    {
      $competition = Competition::with(['divisions', 'divisions.awards' => function($query) {
        $query->withoutGlobalScope('organization');
      }])->find($competition_id);

      $schedule = AwardSchedule::with(['items' => function($query) {
        $query->performanceOrder();
      }, 'items.division', 'items.round', 'items.award' => function($query) {
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
      $items = $request->input('items');
      $competition = Competition::find($competition_id);
      $schedule = AwardSchedule::find($schedule_id);

      $schedule->syncItems($items);

      if($request->wantsJson())
      {
        return response()->json($schedule);
      }
    }



    public function destroy(Request $request, $competition_id, $schedule_id)
    {
      $competition = Competition::find($competition_id);
      $schedule = AwardSchedule::find($schedule_id);

      $schedule->delete();

      if($request->wantsJson())
      {
        return response()->json($schedule);
      }

			return redirect()->route('organizer.competition.show',[$competition])->with('success', 'Award Ceremony Schedule Deleted.');

    }
}
