<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Division;

class ResultsController extends Controller
{
    public function division($division_id, $access_code)
    {
      $division = Division::where('access_code', $access_code)->where('is_published', 1)->find($division_id);

      $division = Division::with(['standings' => function($query) {
        $query->orderBy('caption_id', 'DESC');
      }, 'awards' => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'awards.choirs' => function($query) use ($division_id) {
        $query->where('division_id',$division_id);
      }])->where('access_code', $access_code)->where('is_published', 1)->find($division_id);

      //dd($division);

      return view('results.division.show', compact('division'));
    }
}
