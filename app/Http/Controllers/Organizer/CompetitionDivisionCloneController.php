<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;
use App\Division;

use Kris\LaravelFormBuilder\FormBuilder;

class CompetitionDivisionCloneController extends Controller
{
    //
    public function clone($competition_id, $division_id, FormBuilder $formBuilder)
    {
      $competition = Competition::find($competition_id);
      $division = Division::find($division_id);

      $form = $formBuilder->create('Division\CloneForm', [
        'method' => 'POST',
        'model' => $division,
        'url' => route('organizer.competition.division.clone.store',[$competition,$division])
      ]);

      return view('competition_division.organizer.clone', compact('competition','division','form'));
    }


    public function store(Request $request, $competition_id, $division_id)
    {
      $competition = Competition::find($competition_id);
      $division = Division::with('rounds','choirs','judges')->find($division_id);

      $division_clone = $division->replicate();

      // Set the new division name
      if($request->filled('division_name'))
      {
        $division_clone->name = $request->input('division_name');
      }
      else {
        $division_clone->name = $division->name . '- Copy';
      }

      $division_clone->save();

      // Clone rounds
      if($request->filled('clone_rounds'))
      {
        foreach($division->rounds as $round)
        {
          $new_round = $round->replicate();
          $new_round->is_completed = false;
          $new_round->is_scoring_active = false;
          $division_clone->rounds()->save($new_round);
        }
      }

      // Clone judges
      if($request->filled('clone_judges'))
      {
        foreach($division->judges as $judge)
        {
          $data = ['caption_id' => $judge->pivot->caption_id];
          $division_clone->judges()->attach($judge, $data);
        }
      }

      return redirect()->route('organizer.competition.division.show',[$competition,$division_clone]);

    }
}
