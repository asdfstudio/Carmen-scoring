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

class ScheduleController extends Controller
{
    /**
     * [index description]
     * @param  [type] $competition_id [description]
     * @return [type]                 [description]
     */
    public function index($competition_id)
    {
      $competition = Competition::with('schedules')->find($competition_id);

      return view('schedule.organizer.index', compact('competition', 'schedules'));
    }

    /**
     * [create description]
     * @param  [type]      $competition_id [description]
     * @param  Request     $request        [description]
     * @param  FormBuilder $formBuilder    [description]
     * @return [type]                      [description]
     */
    public function create($competition_id, Request $request, FormBuilder $formBuilder)
    {
      $competition = Competition::find($competition_id);
      $form = $formBuilder->create('Schedule\CreateForm', [
        'method' => 'post',
        'url' => route('organizer.competition.schedule.store', [$competition]),
      ]);
      return view('schedule.organizer.create', compact('competition', 'schedule', 'form'));
    }

    /**
     * [store description]
     * @param  Request     $request        [description]
     * @param  FormBuilder $formBuilder    [description]
     * @param  [type]      $competition_id [description]
     * @return [type]                      [description]
     */
    public function store(Request $request, FormBuilder $formBuilder, $competition_id)
    {
      $competition = Competition::find($competition_id);

      $form = $formBuilder->create('Schedule\CreateForm', [
        'model' => false,
      ]);

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      $schedule = new Schedule;
      $schedule->name = $request->input('name');
      $competition->schedules()->save($schedule);

      if($request->wantsJson())
      {
        return response()->json($schedule);
      }

      return redirect()->route('organizer.competition.schedule.show',[$competition, $schedule])->with('success', 'Schedule updated.');
    }

    /**
     * [show description]
     * @param  [type]      $competition_id [description]
     * @param  [type]      $schedule_id    [description]
     * @param  FormBuilder $formBuilder    [description]
     * @return [type]                      [description]
     */
    public function show($competition_id, $schedule_id, FormBuilder $formBuilder)
    {
      $competition = Competition::find($competition_id);
      $schedule = Schedule::with(['items', 'items.round', 'items.round.division', 'items.choir'])->find($schedule_id);

      $deleteForm = $formBuilder->create('GenericDeleteForm', [
        'url' => route('organizer.competition.schedule.destroy',[$competition, $schedule])
      ]);

      return view('schedule.organizer.show', compact('competition', 'schedule', 'deleteForm'));
    }

    /**
     * [builder description]
     * @param  [type]      $competition_id [description]
     * @param  [type]      $schedule_id    [description]
     * @param  FormBuilder $formBuilder    [description]
     * @return [type]                      [description]
     */
    public function builder($competition_id, $schedule_id, FormBuilder $formBuilder)
    {
      $competition = Competition::with('divisions', 'divisions.rounds', 'divisions.rounds.choirs')->find($competition_id);

      $schedule = Schedule::with(['items', 'items.round', 'items.round.division', 'items.choir'])->find($schedule_id);

      $excludedScheduleItems = ScheduleItem::whereHas('schedule', function($query) use ($competition_id) {
        $query->where('competition_id', $competition_id);
      })->get();

      return view('schedule.organizer.builder', compact('competition', 'schedule', 'excludedScheduleItems'));
    }

    /**
     * [builderStore description]
     * @param  [type]      $competition_id [description]
     * @param  [type]      $schedule_id    [description]
     * @param  FormBuilder $formBuilder    [description]
     * @param  Request     $request        [description]
     * @return [type]                      [description]
     */
    public function builderStore($competition_id, $schedule_id, FormBuilder $formBuilder, Request $request)
    {
      $items = $request->input('items');
      $competition = Competition::find($competition_id);
      $schedule = Schedule::find($schedule_id);

      $schedule->syncItems($items);

      if($request->wantsJson())
      {
        return response()->json($schedule);
      }
    }

    /**
     * [edit description]
     * @param  [type]      $competition_id [description]
     * @param  [type]      $schedule_id    [description]
     * @param  FormBuilder $formBuilder    [description]
     * @return [type]                      [description]
     */
    public function edit($competition_id, $schedule_id, FormBuilder $formBuilder)
    {
      $competition = Competition::find($competition_id);
      $schedule = Schedule::find($schedule_id);
      $form = $formBuilder->create('Schedule\CreateForm', [
        'model' => $schedule,
        'method' => 'post',
        'url' => route('organizer.competition.schedule.update', [$competition, $schedule]),
      ]);
      return view('schedule.organizer.edit', compact('competition', 'schedule', 'form'));
    }


    /**
     * [update description]
     * @param  Request     $request        [description]
     * @param  FormBuilder $formBuilder    [description]
     * @param  [type]      $competition_id [description]
     * @param  [type]      $schedule_id    [description]
     * @return [type]                      [description]
     */
    public function update(Request $request, FormBuilder $formBuilder, $competition_id, $schedule_id)
    {
      $competition = Competition::find($competition_id);
      $schedule = Schedule::find($schedule_id);

      $form = $formBuilder->create('Schedule\CreateForm', [
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

      return redirect()->route('organizer.competition.schedule.show',[$competition, $schedule])->with('success', 'Schedule updated.');
    }


    /**
     * [destroy description]
     * @param  Request $request        [description]
     * @param  [type]  $competition_id [description]
     * @param  [type]  $schedule_id    [description]
     * @return [type]                  [description]
     */
    public function destroy(Request $request, $competition_id, $schedule_id)
    {
      $competition = Competition::find($schedule_id);
      $schedule = Schedule::find($schedule_id);
			//$this->authorize('destroy',$competition);

      $schedule->delete();

      if($request->wantsJson())
      {
        return response()->json($schedule);
      }

			return redirect()->route('organizer.competition.schedule.index', $competition);
    }
}
