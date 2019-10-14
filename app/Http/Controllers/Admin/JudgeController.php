<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Judge;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class JudgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
				$judges = Judge::get();
				return view('judge.index', compact('judges'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $this->authorize('create','App\Judge');

				$form = $formBuilder->create('PersonForm', [
					'method' => 'POST',
					'url' => route('admin.judge.store')
				]);

				return view('person.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $this->authorize('create','App\Judge');

				$form = $formBuilder->create('PersonForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

				// Create the organization
				$judge = Judge::create($request->input());

				// Set flash data and redirect
				return redirect()->route('admin.judge.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Judge $judge)
    {
				//$judge = Judge::find($id);

        $this->authorize('show', $judge);

				return view('judge.show', compact('judge') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, Judge $judge)
    {
				//$judge = Judge::find($id);

				$this->authorize('update',$judge);

				$form = $formBuilder->create('PersonForm', [
					'method' => 'PATCH',
					'url' => route('admin.judge.update', [$judge]),
					'model' => $judge
				]);

				return view('judge.edit', compact('form','judge'));
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
				$judge = Judge::find($id);

				$this->authorize('update',$judge);

				// Validate input
				$form = $formBuilder->create('PersonForm');

				// Validate input
				if (!$form->isValid()) {
           return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get the input
				$input = $request->input();

				// Update the organization
				$judge->first_name = $input['first_name'];
				$judge->last_name = $input['last_name'];
				$judge->email = $input['email'];
				$judge->save();


				// Set flash data

				// Redirect
				return redirect()->route('admin.judge.show',[$judge]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $judge = Judge::find($id);

				// method 1
				$this->authorize('destroy',$judge);

				dd($judge);
    }
}
