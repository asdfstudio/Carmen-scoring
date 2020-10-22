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
        'url' => route('organizer.competition.clone.store',[$competition]),
        'data' => [ 'competition_name' => $competition->name ]
      ]);

      return view('competition.organizer.clone', compact('competition','form'));
    }


    public function store(Request $request, $competition_id)
    {

      // Begin DB transaction
      $competition_clone = DB::transaction(function() use ($request, $competition_id) {

        $competition = Competition::with('place','divisions')->find($competition_id);

        $competition_clone = $competition->replicate(['name']);


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

        // Clone Competition Rounds
        if($request->filled('clone_rounds'))
        {
          foreach($competition->rounds as $round)
          {
            $new_round = $round->replicate();
            $new_round->competition_id = $competition_clone->id;
            $new_round->save();

            // Clone division
            if($request->filled('clone_divisions'))
            {
              foreach($round->divisions as $division)
              {
                $new_division = $division->replicate();
                $new_division->round_id = $new_round->id;
                $new_division->save();

                // Clone division judges
                if($request->filled('clone_judges'))
                {
                  foreach($round->judges as $judge)
                  {
                    $new_round->judges()->attach($judge->id, ['caption_id' => $judge->pivot->caption_id]);
                  }
                }
              }
            }
          }
        }

        return $competition_clone;

      }); // end DB transaction

      return redirect()->route('organizer.competition.show',[$competition_clone]);

    }
}
