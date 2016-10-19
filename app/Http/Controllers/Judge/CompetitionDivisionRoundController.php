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

    $competition = Competition::find($competition_id);

    $round = Round::with(['division','division.competition','division.judges' => function($query) use ($judge_id) {
        $query->where('judge_id',$judge_id)->first();
      }, 'division.judges.captions' => function($query) use ($division_id) {
        $query->where('division_id',$division_id);
      }, 'division.judges.captions.criteria','division.choirs','division.rounds'])->find($round_id);

    $captions = Caption::get();

    $division = $round->division;

    return view('competition_division_round.judge.summary',compact('rawScores','captions','round','competition','division'));
  }




    public function details($competition,$division_id,$round_id)
		{
      echo 'a';
			$judge_id = Auth::user()->person_id;

			$rawScores = RawScore::with('judge','choir')->where('division_id',$division_id)->where('round_id',$round_id)->where('judge_id',$judge_id)->get();

			//dd($rawScores);

			//$division = Division::with('choirs','judges','judges.captions','competition','competition.organization')->find($division_id);

			$round = Round::with(['division','division.competition','division.judges' => function($query) use ($judge_id) {
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




		public function show_ranked($competition,$division_id,$round_id)
		{

			$judge_id = Auth::user()->person_id;


			$scoreboard = new Scoreboard([
				'division_id' => $division_id,
				'round_id' => $round_id,
				'judge_id' => $judge_id
			]);

			$rankedScores = $scoreboard->ranked_scores();

			//$round = Round::with('division','division.competition')->find($round_id);

			$round = Round::with(['division','division.competition','division.judges' => function($query) use ($judge_id) {
					$query->where('judge_id',$judge_id)->first();
				}, 'division.judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}, 'division.judges.captions.criteria','division.choirs'])->find($round_id);

			$captions = Caption::get();

			return view('competition_division_round.judge.show_ranked',compact('rankedScores','captions','round'));
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

			//dd($choirScores);

			// Save multiple scores
			if($choirScores)
			{
				$response = $scorekeeper->save_multiple_choirs_scores($choirScores);
				dd($response);
			}
		}
}
