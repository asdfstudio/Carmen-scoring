<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;
use App\Choir;
use App\School;
use App\Place;
use App\Director;
use App\Person;

use Kris\LaravelFormBuilder\FormBuilder;

use Event;
use App\Events\DivisionChoirCreated;
use App\Events\DivisionChoirRemoved;

class CompetitionDivisionChoirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormBuilder $formBuilder, $competition_id,$division_id)
    {
        $division = Division::with('competition','choirs', 'choirs.directors')->find($division_id);
				//$competition = Competition::with('organization','place','divisions')->find($competition_id);
				//dd($division);

        //$choir_ids = $division->choirs->pluck('id');
        //$directors = Director::whereIn('choir_id', $choir_ids)->get();
        //dd($directors);

        /*$directors = collect();

        $division->choirs->each(function($choir,$key) use ($directors) {
          foreach($choir->directors as $director)
          {
            $directors->push($director);
          }

        });

        dd($directors->pluck('email')->toArray());*/

        $deleteForm = $formBuilder->create('GenericDeleteForm', [
					'method' => 'DELETE'
					//'url' => route('organizer.competition.division.choir.destroy',[$division->competition,$division,$judge])
				]);

        $deleteForm->modify('submit','submit',['label' => 'Remove Choir']);

				return view('competition_division_choir.organizer.index', compact('division', 'deleteForm'));
    }


    public function setup($competition_id,$division_id, FormBuilder $formBuilder)
    {
        $division = Division::with('competition','choirs')->find($division_id);
        $competition = $division->competition;

        $form = $formBuilder->create('Choir\CreateChoirsForm', [
          'url' => route('organizer.competition.division.choir.setup.store',[$competition,$division])
        ]);

        return view('competition_division_choir.organizer.setup', compact('division','competition','form'));
    }


    public function storeMultiple(Request $request, FormBuilder $formBuilder, $competition_id, $division_id)
    {
        $form = $formBuilder->create('Choir\CreateChoirsForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $division = Division::with('competition','choirs')->find($division_id);
        $competition = $division->competition;

        foreach($request->input('choirs') as $choir_input)
        {
          // Create school
          if(!empty($choir_input['school']) AND !empty($choir_input['school']['name']))
          {
            //echo '1';
            $school = School::create($choir_input['school']);
            $school_id = $school->id;

            // Create place for school
            if(!empty($choir_input['school']['place']))
            {
              $place = new Place($choir_input['school']['place']);
              $school->place()->save($place);
            }

            //dd($school);
          }
          elseif(!empty($choir_input['school_id']))
          {
            //echo '2';
            $school_id = $choir_input['school_id'];
            $school = School::find($school_id);
            //dd($school);
          }

          //dd($choir_input);

          // Create a choir
          if(!empty($choir_input['name']))
          {
            $choir = $school->choirs()->create([
              'name' => $choir_input['name']
            ]);

            $choir_id = $choir->id;
          }
          elseif(!empty($choir_input['choir_id']))
          {
            $choir_id = $choir_input['choir_id'];
          }

          // If choir id, attach choir to division
          if($choir_id)
          {
            $division->choirs()->attach($choir_id);
          }
        }


				// Set flash data and redirect
				return redirect()->route('organizer.competition.division.choir.index',[$competition,$division]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($competition_id, $division_id, FormBuilder $formBuilder)
    {
				$division = Division::with('competition','choirs')->find($division_id);

        $this->authorize('addChoir', $division);

        // Division choirs
        $choirs = $division->choirs->pluck('full_name', 'id')->toArray();

        // All choirs
        $choirs = Choir::all()->pluck('full_name', 'id')->toArray();
        //dd($choirs);

        $form = $formBuilder->create('Choir\CreateChoirForm', [
					'method' => 'POST',
          'class' => '',
          'data' => $choirs,
					'url' => route('organizer.competition.division.choir.store',[$division->competition,$division])
				]);



				return view('competition_division_choir.organizer.create', compact('division','form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($competition_id, $division_id, Request $request, FormBuilder $formBuilder)
    {
        //$this->authorize('create','App\Choir');
				$form = $formBuilder->create('Choir\CreateChoirForm');
        
				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				// Get the division
				$division = Division::with('competition','choirs')->find($division_id);

        //die(print_r($request->all(), true));
        
        // Create school and location
				if($request->filled('school.name'))
				{
          $school = new School();
          $school->name = $request->input('school.name');
					$school->save();

          // Create a school location
          if($request->filled('school.place'))
  				{
            $place = new Place($request->input('school.place'));
            $school->place()->save($place);
          }
				}
        // Retrieve school
        elseif($request->input('school_id')) {
          $school = School::find($request->input('school_id'));
        }
        else {
          $school = false;
        }

        // Create or retrieve choir
				if($school AND $request->filled('name'))
				{
					$choir = new Choir();
          $choir->name = $request->input('name');
          $school->choirs()->save($choir);
				}
        elseif($request->filled('choir_id'))
        {
          $choir = Choir::with('school')->find($request->input('choir_id'));
        }

        //die(print_r($choir, true));
        
        /*
        // Create a director and attach to choir
        if($request->filled('director'))
				{
          $director = new Director();
          $director->fill($request->input('director'));
					$choir->directors()->save($director);
				}
        */
        
        // If the form is submitted with an existing person ID...
        if($request->has('director.person_id')){
          
          $director_id = $request->input('director.person_id');
          
          // Make sure this person is recorded as a director in the database.
          $person = Person::with('types')->find($director_id);
          if(!$person->getIsDirectorAttribute()){
            $person->types()->syncWithoutDetaching([2]);
          }
          
          // Attach the person to this choir.
          $choir->directors()->syncWithoutDetaching([intval($director_id)]);

        } elseif($request->has('director.first_name')) {

          // Otherwise, the intention is to create a new director.
          $director = new Director();
          $director->first_name = $request->input('director.first_name');
          $director->last_name = $request->input('director.last_name');
          $director->email = $request->input('director.email');
          if($request->has('director.emails_additional')){
            $director->emails_additional = $request->input('director.emails_additional');
          }
          if($request->has('director.tel')){
            $director->tel = $request->input('director.tel');
          }
          $choir->directors()->save($director);

        }
      
				// Attach choir to the division
				if($choir)
				{
					$division->choirs()->attach($choir->id);
				}

        Event::fire(new DivisionChoirCreated($division, $choir));

        $successMessage = "$choir->name has been added to this division.";

        if($request->wantsJson())
        {
          $choir->load('school');
          return response()->json($choir);
        }
        else {
          if($request->exists('submit_create_another'))
          {
            return redirect()->back()->with('success',$successMessage);
          }
          else {
            return redirect()->route('organizer.competition.division.choir.index', [$division->competition, $division])->with('success',$successMessage);
          }
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($competition_id, $division_id, $choir_id, FormBuilder $formBuilder)
    {
				$division = Division::with('competition')->find($division_id);
        $choir = Choir::with('school')->find($choir_id);

				$form = $formBuilder->create('DeleteChoirForm', [
					'method' => 'DELETE',
					'url' => route('organizer.competition.division.choir.destroy',[$division->competition,$division,$choir])
				]);

				return view('competition_division_choir.organizer.show', compact('division','choir','form'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($competition_id, $division_id, $choir_id, FormBuilder $formBuilder, Request $request)
    {
        $division = Division::with('competition')->find($division_id);
        $choir = Choir::with('school')->find($choir_id);

				$division->choirs()->detach($choir_id);

        Event::fire(new DivisionChoirRemoved($division, $choir));

        if($request->wantsJson())
        {
          return response()->json($choir_id);
        }
        else {
          // Set flash data and redirect
  				return redirect()->route('organizer.competition.division.choir.index',[$division->competition, $division])->with('success',"$choir->name has been removed from this division." );
        }


    }
}
