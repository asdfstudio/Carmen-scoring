<?php

namespace App\Http\Controllers\Admin;

use App\Caption;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;

class CaptionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      $captions = Caption::orderBy('name', 'asc')->get();

      return view('caption.admin.index', compact('captions'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(FormBuilder $formBuilder)
  {
      $form = $formBuilder->create('Caption\CreateForm', [
        'method' => 'POST',
        'url' => route('admin.caption.store')
      ]);

      return view('caption.admin.create', compact('form'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, FormBuilder $formBuilder)
  {
    $form = $formBuilder->create('Caption\CreateForm', [
      'method' => 'POST',
      'url' => route('admin.caption.store')
    ]);

    // Validate input
    if (!$form->isValid()) {
       return redirect()->back()->withErrors($form->getErrors())->withInput();
    }

    // Create it
    $caption = Caption::create($request->input());

    // Set flash data and redirect
    return redirect()->route('admin.caption.index')->with('success',"$caption->name successfully created.");
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
  public function edit($id, FormBuilder $formBuilder)
  {
    $caption = Caption::find($id);

    $form = $formBuilder->create('Caption\CreateForm', [
      'method' => 'PATCH',
      'url' => route('admin.caption.update', $id),
      'model' => $caption
    ]);

    return view('caption.admin.edit', compact('form'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id, FormBuilder $formBuilder)
  {
    $form = $formBuilder->create('Caption\CreateForm');

    // Validate input
    if (!$form->isValid()) {
       return redirect()->back()->withErrors($form->getErrors())->withInput();
    }

    // Create it
    $caption = Caption::find($id);
    $caption->fill($request->input());
    $caption->save();

    // Set flash data and redirect
    return redirect()->route('admin.caption.index')->with('success',"$caption->name successfully updated.");
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
      //
  }
}
