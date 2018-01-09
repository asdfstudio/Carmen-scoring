<?php

namespace App\Http\Controllers\Admin;

use App\Caption;
use App\Criterion;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $captions = Caption::get();
        $criteria = Criterion::with('sheets')->orderBy('name', 'asc')->get();

        return view('criteria.admin.index', compact('criteria', 'captions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $captions = Caption::get()->pluck('name', 'id')->toArray();

        $form = $formBuilder->create('Criteria\CriterionForm', [
          'method' => 'POST',
          'url' => route('admin.criteria.store'),
          'data' => [
            'captions' => $captions
          ]
        ]);

        return view('criteria.admin.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
      $form = $formBuilder->create('Criteria\CriterionForm', [
        'method' => 'POST',
        'url' => route('admin.criteria.store'),
        'data' => [
          'captions' => false
        ]
      ]);

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      // Create it
      $criterion = Criterion::create($request->input());

      // Set flash data and redirect
      return redirect()->route('admin.criteria.index')->with('success',"$criterion->name successfully created.");
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
      $captions = Caption::get()->pluck('name', 'id')->toArray();
      $criterion = Criterion::find($id);

      $form = $formBuilder->create('Criteria\CriterionForm', [
        'method' => 'PATCH',
        'url' => route('admin.criteria.update', $id),
        'model' => $criterion,
        'data' => [
          'captions' => $captions
        ]
      ]);

      return view('criteria.admin.edit', compact('form'));
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
      $form = $formBuilder->create('Criteria\CriterionForm', [
        'data' => [
          'captions' => false
        ]
      ]);

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      // Create it
      $criterion = Criterion::find($id);
      $criterion->fill($request->input());
      $criterion->save();

      // Set flash data and redirect
      return redirect()->route('admin.criteria.index')->with('success',"$criterion->name successfully updated.");
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
