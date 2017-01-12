<?php

namespace App\Http\Controllers\Judge;

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

use App\Carmen\Scorekeeper;
use App\Carmen\Scoreboard;

use Auth;

class CompetitionDivisionRoundController extends Controller
{


    public function summary($competition_id,$division_id,$round_id)
    {

      $judge_id = Auth::user()->person_id;

      $rawScores = RawScore::with('judge','choir')
        ->where('division_id',$division_id)
        ->where('round_id',$round_id)
        ->where('judge_id',$judge_id)
        ->get();

      $scoreboard = new Scoreboard(['round_id' => $round_id]);

      $rawScores = $scoreboard->rawScores;
      $weightedScores = $scoreboard->weightedScores;
      //$rankedScores = $scoreboard->rankedScores;


      //$competition = Competition::find($competition_id);

      $round = Round::with(['division','division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      },'division.judges' => function($query) use ($judge_id) {
          $query->where('judge_id',$judge_id)->first();
        }, 'division.judges.captions' => function($query) use ($division_id) {
          $query->where('division_id',$division_id);
        }, 'division.judges.captions.criteria','choirs','division.rounds', 'targets', 'targets.sources' => function($query) use ($round_id) {
          $query->where('id', '!=', $round_id);
        }])->find($round_id);

      //dd($round->targets);

      $captions = Caption::get();

      $division = $round->division;
      $competition = $division->competition;

      return view('competition_division_round.judge.summary',compact('rawScores', 'weightedScores', 'captions', 'round', 'competition', 'division'));
    }



    public function spreadsheet($competition_id,$division_id,$round_id)
    {
      $judge_id = Auth::user()->person_id;

      //$competition = Competition::find($competition_id);
      //$division = Division::find($division_id);

      $round = Round::with(['division', 'division.competition'  => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'division.judges' => function($query) use ($judge_id) {
          $query->where('judge_id',$judge_id)->first();
        }, 'division.judges.captions' => function($query) use ($division_id) {
          $query->where('division_id',$division_id);
        }, 'division.judges.captions.criteria','choirs','division.rounds'])->find($round_id);

      $division = $round->division;
      $competition = $division->competition;

      $judge = $division->judges->first();

      $judge = Judge::with(['captions' => function($query) use ($division_id) {
        $query->where('division_id', $division_id);
      }])->find($judge_id);
      //dd($judge);
      //$captions = $judge->captions;
      $scoreboard = new Scoreboard(['round_id' => $round_id]);

      //dd($scoreboard);

      //$rawScores = $scoreboard->rawScores;
      //$weightedScores = $scoreboard->weightedScores;
      //$rankedScores = $scoreboard->rankedScores;

      return view('competition_division_round.judge.spreadsheet',compact('scoreboard', 'round', 'competition', 'division', 'judge'));
    }



    public function spreadsheet_sources($competition_id, $division_id, $round_id)
    {
      $judge_id = Auth::user()->person_id;

      //$competition = Competition::find($competition_id);
      //$division = Division::find($division_id);

      $round = Round::with(['division', 'division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'division.judges' => function($query) use ($judge_id) {
          $query->where('judge_id',$judge_id)->first();
        }, 'division.judges.captions' => function($query) use ($division_id) {
          $query->where('division_id',$division_id);
        }, 'division.judges.captions.criteria','choirs','division.rounds', 'sources'])->find($round_id);

      $division = $round->division;
      $competition = $division->competition;

      $judge = $division->judges->first();

      $judge = Judge::with(['captions' => function($query) use ($division_id) {
        $query->where('division_id', $division_id);
      }])->find($judge_id);

      //
      $source_division_ids = $round->sources->pluck('division_id')->toArray();

      $source_divisions = Division::with('choirs', 'judges')->whereIn('id', $source_division_ids)->get();

      $source_choirs = collect();

      $source_divisions->each(function($item, $key) use ($source_choirs) {
        if($item->has('choirs'))
        {
          $item->choirs->each(function($choir,$key) use ($source_choirs) {
            return $source_choirs->push($choir);
          });
        }
      });

      $choirs = $source_choirs;


      $source_ids = $round->sources->pluck('id')->toArray();
      $scoreboard = new Scoreboard(['round_id' => $source_ids]);
      //

      //dd($scoreboard);

      //$rawScores = $scoreboard->rawScores;
      //$weightedScores = $scoreboard->weightedScores;
      //$rankedScores = $scoreboard->rankedScores;

      return view('competition_division_round.judge.spreadsheet_sources',compact('scoreboard', 'round', 'competition', 'division', 'judge', 'choirs'));
    }




    public function details($competition,$division_id,$round_id)
		{
			$judge_id = Auth::user()->person_id;

			$rawScores = RawScore::with('judge','choir')->where('division_id',$division_id)->where('round_id',$round_id)->where('judge_id',$judge_id)->get();

			//dd($rawScores);

			//$division = Division::with('choirs','judges','judges.captions','competition','competition.organization')->find($division_id);

			$round = Round::with(['division','division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      },'division.judges' => function($query) use ($judge_id) {
					$query->where('judge_id',$judge_id)->first();
				}, 'division.judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}, 'division.judges.captions.criteria'])->find($round_id);

			//dd($round->division->judges->first());

			/*$division = Division::with(['competition','rounds' => function($query) use ($round_id) {
					$query->where('id',$round_id);
				}, 'judges' => function($query) use ($judge_id) {
					$query->where('judge_id',$judge_id);
				},'judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}])->find($division_id);

			dd($division);*/

			$captions = Caption::get();

			return view('competition_division_round.judge.show',compact('rawScores','captions','round'));
		}



		public function save_scores($competition_id, $division_id, $round_id, Request $request)
		{
			$judge_id = Auth::user()->person_id;

			$scorekeeper = new Scorekeeper([
				'division_id' => $division_id,
				'round_id' => $round_id,
				'judge_id' => $judge_id
			]);

			$choirScores = $request->input('scores', NULL);

			// Save multiple scores
			if($choirScores)
			{
				$response = $scorekeeper->save_multiple_choirs_scores($choirScores);

        return $response;
			}
		}
}
