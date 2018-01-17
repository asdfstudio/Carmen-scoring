<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Penalty;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class PenaltyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormBuilder $formBuilder)
    {
      $this->authorize('showAll', 'App\Penalty');

      $penalties = Penalty::get();

      $deletePenaltyForm = $formBuilder->create('GenericDeleteForm');

      return view('penalty.organizer.index', compact('penalties','deletePenaltyForm'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
      $this->authorize('create','App\Penalty');

      $form = $formBuilder->create('Penalty\CreatePenaltyForm', [
        'class' => '',
        'method' => 'POST',
        'url' => route('organizer.penalty.store')
      ]);

      return view('penalty.organizer.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
      $this->authorize('create','App\Penalty');

      $form = $formBuilder->create('Penalty\CreatePenaltyForm');

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      $data = $request->input();
      $data['organization_id'] = Auth::user()->organization_id;

      // Create the penalty
      $penalty = Penalty::create($data);
      $penalty->save();

      // Set flash data and redirect
      return redirect()->route('organizer.penalty.index')->with('success','Penalty created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, $id)
    {
      $penalty = Penalty::find($id);

      $this->authorize('update',$penalty);

      $form = $formBuilder->create('Penalty\CreatePenaltyForm', [
        'class' => '',
        'method' => 'PATCH',
        'url' => route('organizer.penalty.update', [$penalty]),
        'model' => $penalty
      ]);

      return view('penalty.organizer.edit', compact('form','penalty'));
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
      $penalty = Penalty::find($id);
      $this->authorize('update', $penalty);

      $form = $formBuilder->create('Penalty\CreatePenaltyForm');

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      $data = $request->input();

      // Update the penalty
      $penalty->fill($data);
      $penalty->save();

      // Set flash data and redirect
      return redirect()->route('organizer.penalty.index')->with('success','Penalty updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormBuilder $formBuilder, $id)
    {
      $penalty = Penalty::find($id);

      $this->authorize('destroy',$penalty);

      $penalty->delete();

      return redirect()->route('organizer.penalty.index');
    }
}
