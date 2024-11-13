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
      $round = Round::with(['judges', 'division','division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      },'division.choirs'])->find($round_id);

      if($round->status_slug != 'completed')
      {
        return redirect()->route('judge.round.scores.summary', [$competition_id, $division_id, $round_id])->with('warning', "You cannot view other judge's scores until the type is complete.");
      }

      $judges = $round->judges->unique('id');
      $division = $round->division;
      $competition = $division->competition;




      $scoreboard = new Scoreboard(['round_id' => $round_id]);

      $rawScores = $scoreboard->rawScores;
      $weightedScores = $scoreboard->weightedScores;
      $rankedScores = $scoreboard->rankedScoresForCurrentMethod;

      $ratings = (new Ratings($division))->all();

      $caption_ids = $division->sheet->caption_ids;
      $captions = Caption::forSheet($division->sheet);

			return view('competition_division_round_judge.judge.index', compact('rawScores', 'weightedScores', 'rankedScores', 'scoreboard', 'captions', 'round', 'competition', 'division', 'judges', 'ratings'));
		}


		public function show($competition,$division_id,$round_id,$judge_id)
		{
			$rawScores = RawScore::with('judge','choir')->where('division_id',$division_id)->where('round_id',$round_id)->where('judge_id',$judge_id)->get();

            $round = Round::with(['judges', 'division','division.competition' => function($query) {
                $query->withoutGlobalScope('organization');
            }, 'division.choirs', 'round.judges.captions' => function($query) use ($round_id) {
                $query->where('round_id',$round_id);
            }, 'round.judges.captions.criteria','division.choirs'])->find($round_id);

			$judge = Judge::find($judge_id);

			$captions = Caption::forSheet($round->division->sheet);

			return view('competition_division_round_judge.judge.show',compact('rawScores','captions','round','judge'));
		}

}
