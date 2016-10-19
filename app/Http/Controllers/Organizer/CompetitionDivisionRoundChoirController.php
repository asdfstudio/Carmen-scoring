<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Division;
use App\Competition;
use App\Choir;
use App\Round;
use App\RawScore;
use App\Caption;

use App\Carmen\WeightedScores;
use App\Carmen\RankedScores;

class CompetitionDivisionRoundChoirController extends Controller
{

    public function show($competition_id,$division_id,$round_id,$choir_id)
		{
      $competition = Competition::with('divisions')->find($competition_id);
      $divisions = $competition->divisions;
			$choir = Choir::with(['penalties' => function($query) use ($round_id){
        $query->where('round_id', $round_id);
      }])->find($choir_id);
			$round = Round::find($round_id);
			$division = Division::with(['choirs','rounds','sheet','sheet.criteria','sheet.criteria.caption','competition','judges' => function ($query) {
					$query->groupBy('judge_id');
          //$query->distinct();
				}])->find($division_id);

      //
      $captions = Caption::get();
      $rounds = $division->rounds;
			$rawScores = RawScore::with('judge', 'criterion', 'criterion.caption')->where('division_id', $division_id)->where('round_id', $round_id)->get();

      $weightedScoresClass = new WeightedScores($rawScores,        $division->caption_weighting_id);
      $weightedScores = $weightedScoresClass->all();

      $rankedScores = new RankedScores($weightedScores);


			return view('competition_division_round_choir.organizer.show',compact('competition', 'rawScores', 'weightedScores', 'rankedScores', 'choir', 'round', 'division', 'rounds', 'divisions', 'captions'));

		}


    public function assign_penalty($competition_id, $division_id, $round_id, $choir_id)
    {
      // Get choir
      $choir = Choir::with(['penalties' => function($query) use ($round_id){
        $query->where('round_id', $round_id);
      }])->find($choir_id);
      $selected_penalties = $choir->penalties;
      //dd($choir);

      // Get all available penalties
      $division = Division::find($division_id);
      $round = Round::find($round_id);

      $penalties = Division::find($division_id)->penalties()->with(['choirs' => function($query) use ($choir_id) {
        $query->where('choir_id', $choir_id);
      }])->get();
      //$penalties->load('choirs');
      //dd($penalties);

      // Display
      return view('competition_division_round_choir_penalty.organizer.assign',compact('competition', 'choir', 'round', 'division', 'penalties', 'selected_penalties'));
    }

    public function update_penalty(Request $request, $competition_id, $division_id, $round_id, $choir_id)
    {
      // Get choir
      $choir = Choir::with(['penalties' => function($query) use ($round_id){
        $query->where('round_id', $round_id);
      }])->find($choir_id);

      $division = Division::find($division_id);

      $penalties = $request->input('penalties', array());
      $data = array();

      foreach($penalties as $penalty)
      {
        $data[$penalty] = ['round_id' => $round_id];
      }

      $choir->penalties()->wherePivot('round_id', $round_id)->sync($data);

      //$penalties->load('choirs');
      //dd($penalties);

      // Set flash data and redirect
      return redirect()->route('organizer.competition.division.round.choir.show', [$competition_id, $division_id, $round_id, $choir_id])->with('success','Choir Penalties Assigned.');
    }
}
