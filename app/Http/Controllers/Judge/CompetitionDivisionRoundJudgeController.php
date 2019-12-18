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
use App\Carmen\Scoreboard;
use App\Carmen\Ratings;

use Auth;

class CompetitionDivisionRoundJudgeController extends Controller
{
    public function index($competition_id,$division_id,$round_id)
		{
      $round = Round::with(['division','division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      },'division.choirs','division.judges' => function ($query) {
					$query->groupBy('judge_id');
				}])->find($round_id);

      if($round->status_slug != 'completed')
      {
        return redirect()->route('judge.round.scores.summary', [$competition_id, $division_id, $round_id])->with('warning', "You cannot view other judge's scores until the round is complete.");
      }

      $judges = $round->division->judges->unique('id');
			//$judge_id = Auth::user()->person_id;

      $division = $round->division;
      $competition = $division->competition;




      $scoreboard = new Scoreboard(['round_id' => $round_id]);

      $rawScores = $scoreboard->rawScores;
      $weightedScores = $scoreboard->weightedScores;
      $rankedScores = $scoreboard->rankedScoresForCurrentMethod;

      $ratings = (new Ratings($round))->all();

      $caption_ids = $division->sheet->caption_ids;
      $captions = Caption::forSheet($division->sheet);

			return view('competition_division_round_judge.judge.index', compact('rawScores', 'weightedScores', 'rankedScores', 'scoreboard', 'captions', 'round', 'competition', 'division', 'judges', 'ratings'));
		}


		public function show($competition,$division_id,$round_id,$judge_id)
		{
			$rawScores = RawScore::with('judge','choir')->where('division_id',$division_id)->where('round_id',$round_id)->where('judge_id',$judge_id)->get();

			//$round = Round::with(['division','division.competition','division.choirs'])->find($round_id);

			$round = Round::with(['division','division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      },'division.choirs','division.judges' => function($query) use ($judge_id) {
					$query->where('judge_id',$judge_id)->first();
				}, 'division.judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}, 'division.judges.captions.criteria','division.choirs'])->find($round_id);

			$judge = Judge::find($judge_id);

			$captions = Caption::forSheet($round->division->sheet);

			return view('competition_division_round_judge.judge.show',compact('rawScores','captions','round','judge'));
		}

}
