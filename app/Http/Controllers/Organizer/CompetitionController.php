<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Organization;
use App\Place;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(FormBuilder $formBuilder)
    {
				$competitions = Competition::with('organization', 'place')->active()->get();

				$archivedCompetitions = Competition::with('organization', 'place')->archived()->get();

				$organization =  Organization::find(Auth::user()->organization_id);

        $deleteCompetitionForm = $formBuilder->create('GenericDeleteForm');
				return view('competition.organizer.index', compact('competitions', 'archivedCompetitions', 'deleteCompetitionForm','organization'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create(FormBuilder $formBuilder)
    {
        $this->authorize('create','App\Competition');

				$form = $formBuilder->create('Competition\CreateCompetitionForm', [
					'method' => 'POST',
					'url' => route('organizer.competition.store')
				]);

				return view('competition.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $this->authorize('create','App\Competition');

				$form = $formBuilder->create('Competition\CreateCompetitionForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				$data = $request->input();
				$data['organization_id'] = Auth::user()->organization_id;

				// Create the organization
				$competition = Competition::create($data);

        // Set up place/location
				$place_input = $request->input('place');
				$place = new Place;
				$place->fill($place_input);

				$competition->place()->save($place);

				// Set flash data and redirect
				return redirect()->route('organizer.competition.show',[$competition])->with('success', 'Competition created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, FormBuilder $formBuilder)
    {
				$competition = Competition::with('place','organization','divisions', 'soloDivisions')->find($id);

        $this->authorize('show', $competition);

        $activateScoringForm = $formBuilder->create('Competition\ActivateCompetitionForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.scoring',[$competition])
        ]);

        $completeScoringForm = $formBuilder->create('Competition\CloseCompetitionForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.scoring',[$competition])
        ]);

        $archiveCompetitionForm = $formBuilder->create('Competition\ArchiveForm', [
          'method' => 'POST',
          'url' => route('organizer.competition.scoring',[$competition])
        ]);

				return view('competition.organizer.show', compact('competition', 'activateScoringForm', 'completeScoringForm', 'archiveCompetitionForm'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, Competition $competition)
    {
				//$competition = Competition::find($id);

				$this->authorize('update',$competition);

				$form = $formBuilder->create('Competition\CreateCompetitionForm', [
					'method' => 'PATCH',
					'url' => route('organizer.competition.update', [$competition]),
					'model' => $competition
				]);

				return view('competition.organizer.edit', compact('form','competition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormBuilder $formBuilder, $id)
    {
				$competition = Competition::with('organization','place')->find($id);

				$this->authorize('update',$competition);

				// Validate input
				$form = $formBuilder->create('Competition\CreateCompetitionForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get the input
				$input = $request->input();


				// Update the record
				$competition->name = $input['name'];
        $competition->slug = $input['slug'];
        $competition->access_code = $input['access_code'];
        $competition->use_runner_up_names = intval($input['use_runner_up_names']);
        //$competition->rating_system = array_filter($input['rating_system']);

        //dd($input['rating_system']);

        if($input['begin_date'])
          $competition->begin_date = $input['begin_date'];

        if($input['end_date'])
          $competition->end_date = $input['end_date'];

				$competition->save();


				$place_input = $request->input('place');

				if($competition->place)
					$place = $competition->place;
				else
					$place = new Place;

				$place->address = $place_input['address'];
				$place->address_2 = $place_input['address_2'];
				$place->city = $place_input['city'];
				$place->state = $place_input['state'];
				$place->postal_code = $place_input['postal_code'];
				$competition->place()->save($place);

				// Set flash data

				// Redirect
				return redirect()->route('organizer.competition.show',[$competition])->with('success', 'Competition updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $competition = Competition::find($id);

				//$this->authorize('destroy',$competition);

        $competition->delete();

				return redirect()->route('organizer.competition.index');
    }


    public function scoring($id, Request $request)
    {
      $competition = Competition::with('rounds')->find($id);

      $this->authorize('update',$competition);

      // Activate scoring for
      // all of the division rounds for this competition
      if($request->input('activate'))
      {
        //$is_scoring_active = true;
        $competition->is_completed = false;
        $competition->is_archived = NULL;
      }
      // Complete and deactive scoring for all division rounds
      elseif($request->input('complete'))
      {
        //$is_scoring_active = false;
        $competition->is_completed = true;
        $competition->is_archived = NULL;
      }
      elseif($request->input('archive'))
      {
        $competition->is_completed = true;
        $competition->is_archived = true;
      }
      else {
        return false;
      }

      $competition->save();

      return redirect()->route('organizer.competition.show',[$competition]);
    }
}
