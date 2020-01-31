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
      
      $input = $request->input();
      if(empty($input['is_retired'])){
        $input['is_retired'] = 0;
      } else {
        $input['is_retired'] = 1;
      }
      
      // Create it
      $sheet = Sheet::find($id);
      $sheet->fill($input);
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
      
      $keys_to_unset = array();
      
      // Go through the input and make sure there are no holes.
      foreach($input as $caption_id => $sort_value){
        if($input[$caption_id] !== '0' && empty($input[$caption_id])){
          // If the input is empty, sink it to the bottom with a 'z' prefix
          // and let all empty items sort by caption_id.
          //$input[$caption_id] = 'z'.str_pad($caption_id, 10, '0', STR_PAD_LEFT);
          $keys_to_unset[$caption_id] = $caption_id;
        }
      }
      
      $input = array_diff_key($input, $keys_to_unset);
      
      // Sort the input according to the values given by the user (with unassigned items sorted by caption_id at the end).
      asort($input);
      $reKeyed = array_keys($input);
      
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
