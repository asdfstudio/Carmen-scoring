<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Competition;

use DB;

use Kris\LaravelFormBuilder\FormBuilder;

class CompetitionCloneController extends Controller
{
    //
    public function clone($competition_id, FormBuilder $formBuilder)
    {
      $competition = Competition::find($competition_id);

      $form = $formBuilder->create('Competition\CloneForm', [
        'method' => 'POST',
        'model' => $competition,
        'url' => route('organizer.competition.clone.store',[$competition])
      ]);

      return view('competition.organizer.clone', compact('competition','form'));
    }


    public function store(Request $request, $competition_id)
    {


      // Begin DB transaction
      $competition_clone = DB::transaction(function() use ($request, $competition_id) {


        $competition = Competition::with('place','divisions',
          'divisions.rounds','divisions.choirs',
          'divisions.judges')->find($competition_id);

        $competition_clone = $competition->replicate(['name']);

        //dd($competition_clone);

        // Set the new competition name
        if($request->filled('competition_name'))
        {
          $competition_clone->name = $request->input('competition_name');
        }
        else {
          $competition_clone->name = $competition->name . '- Copy';
        }

        $competition_clone->save();

        // Clone Competition location
        if($competition->place)
        {
          $place = $competition->place->replicate();
          $competition_clone->place()->save($place);
        }

        // Clone divisions
        if($request->filled('clone_divisions'))
        {
          //$divisions = $competition->divisions->replicate();
          //$competition_clone->divisions()->save($divisions);
          foreach($competition->divisions as $division)
          {
            // Clone division
            $new_division = $division->replicate();
            $competition_clone->divisions()->save($new_division);

            // Clone division rounds
            if($request->filled('clone_rounds'))
            {
              foreach($division->rounds as $round)
              {
                $new_round = $round->replicate();
                $new_division->rounds()->save($new_round);
              }
            }

            // Clone dvision judges
            if($request->filled('clone_judges'))
            {
              foreach($division->judges as $judge)
              {
                //$new_judge = $judge->replicate();
                //$new_division->judges()->save($new_judge);
                $data = ['caption_id' => $judge->pivot->caption_id];
                $new_division->judges()->attach($judge, $data);
              }
            }
          }
        }

        return $competition_clone;

      }); // end DB transaction

      return redirect()->route('organizer.competition.show',[$competition_clone]);

    }
}
