<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Choir;
use App\Place;
use App\Person;
use App\Director;

use Auth;

use Kris\LaravelFormBuilder\FormBuilder;

class ChoirDirectorController extends Controller
{

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Choir $choir, FormBuilder $formBuilder)
  {
    $this->authorize('update','App\Choir');

    $form = $formBuilder->create('Director\CreateDirectorForm', [
      'method' => 'POST',
      'url' => route('admin.choir.director.store', [$choir])
    ]);

    return view('choir_director.admin.create', compact('form', 'choir'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Choir $choir, Request $request, FormBuilder $formBuilder)
  {
    $this->authorize('update','App\Choir');

    $form = $formBuilder->create('Director\CreateDirectorForm');

    // If the form is submitted with an existing person ID...
    if($request->has('person_id')){
      
      $director_id = $request->input('person_id');
      
      // Make sure this person is recorded as a director in the database.
      $director = Person::find($director_id)->types()->syncWithoutDetaching([2]);
      
      // Attach the person to this choir.
      $choir->directors()->syncWithoutDetaching([intval($director_id)]);
      
    } else {
      
      // Otherwise, the intention is to create a new director.
      
      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }
      
      // Create the director
      $director = $choir->directors()->create($request->input());
      
    }

      // Set flash data and redirect
      return redirect()->route('admin.choir.index')->with('success','Choir director successfully added.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Choir $choir)
  {

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(FormBuilder $formBuilder, Choir $choir, Director $director)
  {
    $this->authorize('update','App\Choir');

    $form = $formBuilder->create('Director\CreateDirectorForm', [
      'method' => 'PATCH',
      'url' => route('admin.choir.director.update', [$choir, $director]),
      'model' => $director
    ]);

    $deleteForm = $formBuilder->create('GenericDeleteForm', [
      'method' => 'DELETE',
      'url' => route('admin.choir.director.destroy', [$choir, $director]),
      'model' => $director,
      'button_text' => 'Remove'
    ]);

    return view('choir_director.admin.edit', compact('form', 'choir', 'director', 'deleteForm'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, FormBuilder $formBuilder, Choir $choir, Director $director)
  {
    $this->authorize('update','App\Choir');

    $form = $formBuilder->create('Director\CreateDirectorForm', [
      'model' => $director
    ]);

    // Validate input
    if (!$form->isValid()) {
       return redirect()->back()->withErrors($form->getErrors())->withInput();
    }

    // Create the organization
    $director->fill($request->input());
    $director->save();

    // Set flash data and redirect
    return redirect()->route('admin.choir.index')->with('success','Choir director successfully updated.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Choir $choir, Director $director)
  {
    $this->authorize('destroy',$choir);

    $choir->directors()->detach($director->id);

    // Set flash data and redirect
    return redirect()->route('admin.choir.show', [$choir])->with('success','Choir director successfully removed.');
  }
}
