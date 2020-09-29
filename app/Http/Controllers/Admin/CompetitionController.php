<?php

namespace App\Http\Controllers\Admin;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
				$competitions = Competition::with('organization','place')->get();
				return view('competition.index', compact('competitions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $this->authorize('create','App\Competition');

				$form = $formBuilder->create('CompetitionForm', [
					'method' => 'POST',
					'url' => route('admin.competition.store')
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

				$form = $formBuilder->create('CompetitionForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				// Create the organization
				$competition = Competition::create($request->input());


				$place_input = $request->input('place');


				$place = new Place;
				$place->address = $place_input['address'];
				$place->address_2 = $place_input['address_2'];
				$place->city = $place_input['city'];
				$place->state = $place_input['state'];
				$place->postal_code = $place_input['postal_code'];
				$competition->place()->save($place);

				// Set flash data and redirect
				return redirect()->route('admin.competition.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Competition $competition)
    {
				//$competition = Competition::find($id);

        $this->authorize('show', $competition);

				return view('competition.show', ['competition' => $competition]);
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

				$form = $formBuilder->create('CompetitionForm', [
					'method' => 'PATCH',
					'url' => route('admin.competition.update', [$competition]),
					'model' => $competition
				]);

				return view('competition.edit', compact('form','competition'));
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
				$form = $formBuilder->create('CompetitionForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get the input
				$input = $request->input();

				// Update the record
				$competition->name = $input['name'];
				$competition->organization_id = $input['organization_id'];
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
				return redirect()->route('admin.competition.show',[$competition]);
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

				$this->authorize('destroy',$competition);

				dd($competition);
    }
}
