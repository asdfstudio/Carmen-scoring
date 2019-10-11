<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Choir;
use App\Place;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class ChoirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
				$choirs = Choir::with('school', 'school.place')->withoutGlobalScope('organization')->get();

        return view('choir.admin.index', compact('choirs'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $this->authorize('create','App\Choir');

				$form = $formBuilder->create('ChoirForm', [
					'method' => 'POST',
					'url' => route('admin.choir.store')
				]);

				return view('choir.admin.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $this->authorize('create','App\Choir');

				$form = $formBuilder->create('ChoirForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				// Create the organization
				$choir = Choir::create($request->input());

				// Set flash data and redirect
				return redirect()->route('admin.choir.index')->with('success','Choir successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Choir $choir)
    {
				//$choir = Choir::find($id);

        $this->authorize('show', $choir);

				return view('choir.admin.show', ['choir' => $choir]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, Choir $choir)
    {
				//$choir = Choir::find($id);

				$this->authorize('update',$choir);

				$form = $formBuilder->create('ChoirForm', [
					'method' => 'PATCH',
					'url' => route('admin.choir.update', [$choir]),
					'model' => $choir
				]);

				return view('choir.admin.edit', compact('form','choir'));
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
				$choir = Choir::with('school','school.place')->find($id);

				$this->authorize('update',$choir);

				// Validate input
				$form = $formBuilder->create('ChoirForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get the input
				$input = $request->input();

				// Update the record
				$choir->name = $input['name'];
				$choir->school_id = $input['school_id'];
				$choir->save();

				// Set flash data

				// Redirect
				return redirect()->route('admin.choir.index')->with('success',"Choir $choir->name has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $choir = Choir::find($id);

				$this->authorize('destroy',$choir);

				dd($choir);
    }
}
