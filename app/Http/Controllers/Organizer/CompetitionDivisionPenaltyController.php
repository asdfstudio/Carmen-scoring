<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;
use App\Penalty;

use Auth;

use DB;

use Kris\LaravelFormBuilder\FormBuilder;

class CompetitionDivisionPenaltyController extends Controller
{
    public function index($competition_id, $division_id)
    {
      $division = Division::with('competition', 'penalties')->find($division_id);
      $competition = $division->competition;
      $penalties = $division->penalties;

      return view('competition_division_penalty.organizer.index', compact('competition','division', 'penalties'));
    }

    public function create(FormBuilder $formBuilder, $competition_id, $division_id)
    {
      $this->authorize('create','App\Penalty');

      $division = Division::find($division_id);

      $form = $formBuilder->create('Penalty\CreatePenaltyForm', [
        'method' => 'POST',
        'url' => route('organizer.competition.division.penalty.store', [$competition_id, $division_id])
      ]);

      return view('competition_division_penalty.organizer.create', compact('form', 'division'));
    }

    public function store(Request $request, FormBuilder $formBuilder, $competition_id, $division_id)
    {
      $division = Division::find($division_id);

      $this->authorize('create','App\Penalty');

      $form = $formBuilder->create('Penalty\CreatePenaltyForm');

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      $data = $request->input();
      $data['organization_id'] = Auth::user()->organization_id;

      // DB transaction
      $penalty = DB::transaction(function () use ($data, $division) {

        // Create the penalty
        $penalty = Penalty::create($data);
        $penalty->save();

        // Associate penalty with division
        $division->penalties()->attach($penalty->id);

        return $penalty;
      });


      $successMessage = "$penalty->name has been created and added to this division.";

      if($request->exists('submit_create_another'))
      {
        return redirect()->back()->with('success',$successMessage);
      }
      else {
        return redirect()->route('organizer.competition.division.penalty.index', [$competition_id, $division_id])->with('success',$successMessage);
      }

    }

    public function manage(FormBuilder $formBuilder, $competition_id, $division_id)
    {
      $division = Division::with('competition','penalties')->find($division_id);
      $competition = $division->competition;
      $selected_penalties = $division->penalties;

      $penalties = Penalty::with(['divisions' => function($query) use ($division_id) {
        $query->where('division_id', $division_id);
      }])->where('organization_id', Auth::user()->organization_id)->get();

      //dd($selected_penalties);

      //$form = $formBuilder->create('Penalty\DivisionPenaltiesForm', [
        //'method' => 'POST',
        //'model' => $penalties,
        //'url' => route('organizer.competition.division.penalty.update', //[$competition_id, $division_id])
      //]);

      return view('competition_division_penalty.organizer.manage', compact('competition','division', 'penalties', 'selected_penalties', 'form'));
    }

    public function update(Request $request, $competition_id, $division_id)
    {
      $division = Division::find($division_id);
      //$this->authorize('update', $penalty);

      $penalties = $request->input('penalties');

      if($penalties == false) $penalties = array();

      $division->penalties()->sync($penalties);

      // Set flash data and redirect
      return redirect()->route('organizer.competition.division.penalty.index', [$competition_id, $division_id])->with('success','Division penalties updated.');
    }
}
