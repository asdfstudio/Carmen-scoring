<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;
use App\Award;

use Auth;

use DB;

use Kris\LaravelFormBuilder\FormBuilder;

class CompetitionDivisionAwardController extends Controller
{
    public function index($competition_id, $division_id)
    {
      $division = Division::with(['competition', 'awards' => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'awards.choirs' => function($query) use ($division_id) {
        $query->where('division_id',$division_id);
      }])->find($division_id);
      
      $this->authorize('showAll', 'App\Award');

      //dd($division->awards);
      $competition = $division->competition;
      $awards = $division->awards;

      //dd($awards);

      return view('competition_division_award.organizer.index', compact('competition', 'division', 'awards'));
    }

    public function create(FormBuilder $formBuilder, $competition_id, $division_id)
    {
      $division = Division::find($division_id);

      //$this->authorize('create', ['App\Award', $division]);
      $this->authorize('createAward' , $division);

      $form = $formBuilder->create('Award\CreateAwardForm', [
        'method' => 'POST',
        'url' => route('organizer.competition.division.award.store', [$competition_id, $division_id]),
        'data' => ['include_sponsor' => true]
      ]);

      return view('competition_division_award.organizer.create', compact('form', 'division'));
    }

    public function store(Request $request, FormBuilder $formBuilder, $competition_id, $division_id)
    {
      $division = Division::find($division_id);

      //$this->authorize('create', ['App\Award', $division]);
      $this->authorize('createAward' , $division);

      $form = $formBuilder->create('Award\CreateAwardForm');

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      $data = $request->input();
      $data['organization_id'] = Auth::user()->organization_id;

      // DB transaction
      $award = DB::transaction(function () use ($data, $division, $request) {

        // Create the award
        $award = Award::create($data);
        $award->save();

        // Associate award with division
        $extra = [];

        if($request->input('sponsor'))
        {
          $extra['sponsor'] = $request->input('sponsor');
        }

        $division->awards()->attach($award->id, $extra);

        return $award;
      });

      $successMessage = "$award->name has been created and added to this division.";

      if($request->exists('submit_create_another'))
      {
        return redirect()->back()->with('success',$successMessage);
      }
      else {
        return redirect()->route('organizer.competition.division.award.index', [$competition_id, $division_id])->with('success',$successMessage);
      }
    }

    public function manage(FormBuilder $formBuilder, Competition $competition, $division_id)
    {
      $division = $competition->divisions()->with(['awards' => function($query) {
        $query->withoutGlobalScope('organization');
      }])->findOrFail($division_id);
      //$division = Division::with('competition','awards')->find($division_id);

      $selected_awards = $division->awards;

      $this->authorize('manage', ['App\Award', $division]);

      $awards = Award::where('organization_id',    Auth::user()->organization_id)->get();

      $standard_awards = Award::withoutGlobalScope('organization')->where('organization_id', NULL)->get();

      return view('competition_division_award.organizer.manage', compact('competition','division', 'awards', 'standard_awards','selected_awards', 'form'));
    }

    public function update(Request $request, Competition $competition, $division_id)
    {
      $division = $competition->divisions()->findOrFail($division_id);

      $this->authorize('manage', ['App\Award', $division]);

      $awards = $request->input('awards', []);
      $sponsors = $request->input('sponsors', []);

      //dd($awards, $sponsors);

      $data = [];

      foreach($awards as $award_id)
      {
        $data[$award_id] = ['sponsor' => $sponsors[$award_id]];
      }

      //dd($data);

      //if($awards == false) $awards = array();

      $division->awards()->sync($data);

      // Set flash data and redirect
      return redirect()->route('organizer.competition.division.award.index', [$competition, $division]);
    }


    public function assign(FormBuilder $formBuilder, Competition $competition, $division_id)
    {
      $division = $competition->divisions()->findOrFail($division_id);
      $division->load(['awards', 'awards.choirs','choirs']);
      //$division = Division::with('awards', 'awards.choirs','choirs')->find($division_id);
      //$competition = $division->competition;
      $awards = $division->awards;

      $this->authorize('assign', ['App\Award', $division]);

      return view('competition_division_award.organizer.assign', compact('competition','division', 'awards'));
    }

    public function update_assignment(Request $request, Competition $competition, $division_id)
    {
      $division = $competition->divisions()->findOrFail($division_id);
      //$division = Division::find($division_id);

      $this->authorize('assign', ['App\Award', $division]);

      $awards = $request->input('awards', []);

      $division->awards()->sync($awards);

      return redirect()->route('organizer.competition.division.award.index', [$competition, $division_id])->with('success', 'Award assignments updated.');
    }
}
