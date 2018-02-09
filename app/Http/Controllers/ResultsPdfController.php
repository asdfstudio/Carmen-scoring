<?php
/*
namespace App\Http\Controllers;

//use PDF;
use App\Division;
use App\Http\Requests;
use Illuminate\Http\Request;

class ResultsPdfController extends Controller
{
    public function test()
    {
      $division_id = 126;

      $division = Division::with([
        'competition' => function($query) {
          $query->withoutGlobalScope('organization');
        },
        'standings' => function($query) {
          $query->orderBy('caption_id', 'DESC');
        },
        'standings.choirs',
        'awards' => function($query) {
          $query->withoutGlobalScope('organization');
        },
        'awards.choirs' => function($query) use ($division_id) {
          $query->where('division_id',$division_id);
        },
        'judges' => function($query) {
          $query->groupBy('judge_id');
        },
        'awardSettings'
      ])->find($division_id);

      //$pdf = PDF::loadView('results.pdf.division', compact('division'));

      return view('results.pdf.division', compact('division'));
      return $pdf->stream();
      //return $pdf->download('test.pdf');
    }
}*/
