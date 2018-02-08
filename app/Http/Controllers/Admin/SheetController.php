<?php

namespace App\Http\Controllers\Admin;

use App\Sheet;
use App\Caption;
use App\Criterion;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;

class SheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $sheets = Sheet::with('criteria')->get();

      return view('sheets.admin.index', compact('sheets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
      $form = $formBuilder->create('Sheets\SheetForm', [
        'method' => 'POST',
        'url' => route('admin.sheet.store')
      ]);

      return view('sheets.admin.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
      $form = $formBuilder->create('Sheets\SheetForm');

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      // Create it
      $sheet = Sheet::create($request->input());

      // Set flash data and redirect
      return redirect()->route('admin.sheet.index')->with('success',"$sheet->name successfully created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $sheet = Sheet::with('criteria')->find($id);
      $sheet->captions = Caption::forSheet($sheet);

      //dd([$sheet->captions->pluck('name', 'id')->toArray()]);

      return view('sheets.admin.show', compact('sheet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
      $sheet = Sheet::find($id);

      $form = $formBuilder->create('Sheets\SheetForm', [
        'method' => 'PATCH',
        'url' => route('admin.sheet.update', $id),
        'model' => $sheet
      ]);

      return view('sheets.admin.edit', compact('form'));
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
      $form = $formBuilder->create('Sheets\SheetForm');

      // Validate input
      if (!$form->isValid()) {
         return redirect()->back()->withErrors($form->getErrors())->withInput();
      }

      // Create it
      $sheet = Sheet::find($id);
      $sheet->fill($request->input());
      $sheet->save();

      // Set flash data and redirect
      return redirect()->route('admin.sheet.index')->with('success',"$sheet->name successfully updated.");
    }

    /**
     * [manage description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function manage($id)
    {
      $captions = Caption::get();
      $sheet = Sheet::with('criteria')->find($id);

      $criteria = Criterion::with('sheets')->orderBy('name', 'asc')->get();

      return view('sheets.admin.manage', compact('sheet', 'criteria', 'captions'));
    }


    public function syncCriteria($id, FormBuilder $formBuilder, Request $request)
    {
      $sheet = Sheet::with('criteria')->find($id);
      $sheet->criteria()->sync($request->input('criteria', []));

      return redirect()->route('admin.sheet.index', $id)->with('success',"$sheet->name successfully updated.");
    }


    public function manageOrder($id)
    {
      $captions = Caption::get();
      $sheet = Sheet::with('criteria')->find($id);
      $criteria = Criterion::with('sheets')->orderBy('name', 'asc')->get();

      return view('sheets.admin.manage-order', compact('sheet', 'criteria', 'captions'));
    }


    public function syncCriteriaOrder($id, FormBuilder $formBuilder, Request $request)
    {
      $sheet = Sheet::with('criteria')->find($id);
      $sheet->criteria()->sync($request->input('criteria', []));

      return redirect()->route('admin.sheet.index', $id)->with('success',"$sheet->name successfully updated.");
    }


    public function manageCaptionOrder($id)
    {
      $captions = Caption::get();
      $sheet = Sheet::with('criteria')->find($id);
      //$criteria = Criterion::with('sheets')->orderBy('name', 'asc')->get();

      return view('sheets.admin.manage-caption-order', compact('sheet', 'captions'));
    }


    public function syncCaptionOrder($id, FormBuilder $formBuilder, Request $request)
    {
      $input = $request->input('captions', []);
      $flipped = array_flip($input);
      ksort($flipped);
      $reKeyed = array_values($flipped);
      //dd([$input, $flipped, $reKeyed]);
      $sheet = Sheet::find($id);
      $sheet->caption_sort_order = $reKeyed;
      $sheet->save();


      return redirect()->route('admin.sheet.index')->with('success',"$sheet->name successfully updated.");
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
