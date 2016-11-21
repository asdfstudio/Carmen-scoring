<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Award;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormBuilder $formBuilder)
    {
      $this->authorize('showAll', 'App\Award');

      $standard_awards = Award::withoutGlobalScope('organization')->where('organization_id', NULL)->get();

      $awards = Award::get();

      $deleteAwardForm = $formBuilder->create('GenericDeleteForm');

      return view('award.organizer.index', compact('standard_awards', 'awards','deleteAwardForm'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
      $this->authorize('create','App\Award');

      $form = $formBuilder->create('Award\CreateAwardForm', [
        'method' => 'POST',
        'url' => route('organizer.award.store')
      ]);

      return view('award.organizer.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
      $this->authorize('create','App\Award');

      $form = $formBuilder->create('Award\CreateAwardForm');

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      $data = $request->input();
      $data['organization_id'] = Auth::user()->organization_id;

      // Create the award
      $award = Award::create($data);
      $award->save();

      // Set flash data and redirect
      return redirect()->route('organizer.award.index')->with('success', 'Award created.');
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
       $award = Award::find($id);

       $this->authorize('update',$award);

       $form = $formBuilder->create('Award\CreateAwardForm', [
         'method' => 'PATCH',
         'url' => route('organizer.award.update', [$award]),
         'model' => $award
       ]);

       return view('award.organizer.edit', compact('form','award'));
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
       $award = Award::find($id);
       $this->authorize('update', $award);

       $form = $formBuilder->create('Award\CreateAwardForm');

       // Validate input
       if (!$form->isValid()) {
          return redirect()->back()->withErrors($form->getErrors())->withInput();
       }

       $data = $request->input();

       // Update the award
       $award->fill($data);
       $award->save();

       // Set flash data and redirect
       return redirect()->route('organizer.award.index')->with('success', 'Award updated.');
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy(FormBuilder $formBuilder, $id)
     {
       $award = Award::find($id);

       $this->authorize('destroy',$award);

       $award->delete();

       return redirect()->route('organizer.award.index');
     }
}
