<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\School;
use App\Place;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
				$schools = School::with('place')->withoutGlobalScope('organization')->get();

        return view('school.admin.index', compact('schools'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $this->authorize('create','App\School');

				$form = $formBuilder->create('SchoolForm', [
					'method' => 'POST',
					'url' => route('admin.school.store')
				]);

				return view('school.admin.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $this->authorize('create','App\School');

				$form = $formBuilder->create('SchoolForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				// Create the organization
				$school = School::create($request->input());

        // Create the place
        $place_data = $request->input('place');
        $place = new Place($place_data);
        $school->place()->save($place);

				// Set flash data and redirect
				return redirect()->route('admin.school.index')->with('success',"$school->name successfully created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
				$school = School::with('choirs','place')->find($id);

        $this->authorize('show', $school);

				return view('school.show', ['school' => $school]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, School $school)
    {
				//$school = School::find($id);

				$this->authorize('update',$school);

				$form = $formBuilder->create('SchoolForm', [
					'method' => 'PATCH',
					'url' => route('admin.school.update', [$school]),
					'model' => $school
				]);

				return view('school.admin.edit', compact('form','school'));
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
				$school = School::with('place')->find($id);

				$this->authorize('update',$school);

				// Validate input
				$form = $formBuilder->create('SchoolForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get the input
				$input = $request->input();

				// Update the record
				$school->name = $input['name'];
				$school->save();

				$place_input = $request->input('place');

				if($school->place)
					$place = $school->place;
				else
					$place = new Place;


				//$place->address = $place_input['address'];
				//$place->address_2 = $place_input['address_2'];
				//$place->city = $place_input['city'];
				//$place->state = $place_input['state'];
				//$place->postal_code = $place_input['postal_code'];
				//$school->place()->save($place);

        // Create the place
        $place_data = $request->input('place');
        $place->fill($place_data);
        //$place = new Place($place_data);
        $school->place()->save($place);


				// Set flash data

				// Redirect
				return redirect()->route('admin.school.index')->with('success',"$school->name has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $school = School::find($id);

				$this->authorize('destroy',$school);

				dd($school);
    }
}
