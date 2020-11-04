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
use App\Judge;
use App\ChoirRoundPenalty;

use App\Carmen\Scoreboard;
use App\Carmen\WeightedScores;
use App\Carmen\RankedScores;

use Kris\LaravelFormBuilder\FormBuilder;

class CompetitionDivisionRoundChoirController extends Controller
{

    public function show(Request $request, $competition_id, $division_id, $round_id, $choir_id)
		{

      $competition = Competition::with('divisions')->find($competition_id);
      $divisions = $competition->divisions;

			$choir = Choir::with(['penalties' => function($query) use ($round_id){
        $query->where('round_id', $round_id);
      }])->find($choir_id);

      $division = Division::with(['choirs', 'round', 'sheet', 'sheet.criteria', 'sheet.criteria.caption', 'competition',
          'round.judges' => function ($query) use($request) {
            if($request->judge_id){
              $query->where('judge_id', $request->judge_id);
            }
            $query->groupBy('judge_id');
          },
          'round.judges.recordings' => function($query) use ($choir_id, $round_id, $request){
            if($request->judge_id){
              $query->where('judge_id', $request->judge_id);
            }else{
              $query->where('judge_id', null);
            }
            $query->where('choir_id', $choir_id)->where('round_id', $round_id);
          }
      ])->find($division_id);

      $round = $division->round;

      $captions = Caption::forSheet($division->sheet);

      $scoreboard = new Scoreboard(['round_id' => $round_id]);
      $rawScores = $scoreboard->extendedRawScores;
      $weightedScores = $scoreboard->extendedRawScores;
      $rankedScores = $scoreboard->rankedScoresForCurrentMethod;

      $judgeList = $round->judges->pluck('full_name','id');
      $judgeList->prepend('Please select a judge', 'null');
      $judge_id= ($request->judge_id)?$request->judge_id:'';

      $penalty_query = ChoirRoundPenalty::with('penalty');
      if(is_array($round_id)){
        $penalty_query->whereIn('round_id', $round_id);
      } else {
        $penalty_query->where('round_id', $round_id);
      }
      $penalties_raw = $penalty_query->get();

      $penalties = collect();

      $penalties_raw->each(function($item, $key) use ($penalties){
        if($item->penalty){
          $penalties->put($key, [
            'choir_id' => $item->choir_id,
            'amount' => $item->penalty->amount,
            'apply_per_judge' => $item->penalty->apply_per_judge
          ]);
        }
      });

			return view('competition_division_round_choir.organizer.show',compact('competition', 'rawScores', 'weightedScores', 'rankedScores', 'choir', 'round', 'division',  'divisions', 'captions', 'judgeList','judge_id'));

		}


    public function assign_penalty(Request $request,$competition_id, $division_id, $choir_id)
    {

      $division = Division::find($division_id);
      $round = $division->round;

      $round_id = $round->id;

      $choir = Choir::with(['penalties' => function($query) use ($round_id){
        $query->where('round_id', $round_id);
      }])->find($choir_id);

      $selected_penalties = $choir->penalties;

      // Get all available penalties
      $penalties = $division->competition->organization->penalties;
      // $penalties = Division::find($division_id)->penalties()->with(['choirs' => function($query) use ($choir_id) {
      //   $query->where('choir_id', $choir_id);
      // }])->get();
      $penalties->load('choirs');

      $redirect = $request->input('redirect');
      // Display
      return view('competition_division_round_choir_penalty.organizer.assign',compact('choir', 'round', 'division', 'penalties', 'selected_penalties', 'redirect'));
    }

    public function update_penalty(Request $request, $competition_id, $division_id, $choir_id)
    {

      $division = Division::find($division_id);
      $round = $division->round;

      $round_id = $round->id;

      $choir = Choir::with(['penalties' => function($query) use ($round_id){
        $query->where('round_id', $round_id);
      }])->find($choir_id);

      $penalties = $request->input('penalties', array());
      $data = array();

      foreach($penalties as $penalty)
      {
        $data[$penalty] = ['round_id' => $round_id];
      }

      $choir->penalties()->wherePivot('round_id', $round_id)->sync($data);

      // Set flash data and redirect
      $redirect = $request->input('redirect');
      if(isset($redirect) && $redirect == 'choir_score'){

        return redirect()->route('organizer.competition.division.round.choir.show', [$competition_id, $division_id, $round, $choir])->with('success','Choir Penalties Assigned.');
      }
      return redirect()->route('organizer.competition.division.penalty.index', [$competition_id, $division_id])->with('success','Choir Penalties Assigned.');
    }


    public function performance_order($competition_id, $division_id, $round_id, FormBuilder $formBuilder)
    {

      $division = Division::with('competition','rounds')->find($division_id);
      $round = Round::with('choirs')->find($round_id);

      $this->authorize('setPerformanceOrder', $round);

      $form = $formBuilder->create('Choir\SortChoirsForm', [
        'url' => route('organizer.competition.division.round.choir.performance_order.update', [$competition_id, $division_id, $round_id]),
        'model' => $round->choirs
      ]);

      $choirs = $round->choirs;

      return view('competition_division_round_choir.organizer.performance_order', compact('division', 'round', 'form', 'choirs'));
    }

    public function update_performance_order($competition_id, $division_id, $round_id, Request $request, FormBuilder $formBuilder)
    {
      $round = Round::with('choirs')->find($round_id);

      $this->authorize('setPerformanceOrder', $round);

      $data = [];

      foreach($request->input('performance_order') as $choir_id => $performance_order)
      {
        $data[$choir_id] = ['performance_order' => $performance_order];
      }

      $round->choirs()->sync($data);

      return redirect()->route('organizer.competition.division.round.index', [$competition_id, $division_id, $round_id])->with('success','Choir performance order updated!');
    }
}
