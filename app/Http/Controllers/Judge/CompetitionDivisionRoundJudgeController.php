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

use Auth;

class CompetitionDivisionRoundJudgeController extends Controller
{
    public function index($competition,$division_id,$round_id)
		{
			
			//$judge_id = Auth::user()->person_id;
			
			$rawScores = RawScore::with('judge','choir')->where('division_id',$division_id)->where('round_id',$round_id)->get();
			
			//dd($rawScores);
			
			$round = Round::with(['division','division.competition','division.choirs','division.judges' => function ($query) {
					$query->groupBy('judge_id');
				}])->find($round_id);
				
			//dd($round);
			
			$captions = Caption::get();
			
			return view('competition_division_round_judge.judge.index',compact('rawScores','captions','round'));
		}
		
		
		public function index_ranked($competition,$division_id,$round_id)
		{
			
			$judge_id = Auth::user()->person_id;
			$judge = Judge::find($judge_id);
			//dd($judge);
			
			
			$scoreboard = new Scoreboard([
				'division_id' => $division_id,
				'round_id' => $round_id
			]);

			$rankedScores = $scoreboard->ranked_scores();
			//dd($rankedScores->first());
			
			$round = Round::with(['division','division.competition','division.choirs','division.judges' => function ($query) {
					$query->groupBy('judge_id');
				}])->find($round_id);
			
			$captions = Caption::get();

			return view('competition_division_round_judge.judge.index_ranked',compact('rankedScores','captions','round'));
		}
		
		
		public function show($competition,$division_id,$round_id,$judge_id)
		{			
			$rawScores = RawScore::with('judge','choir')->where('division_id',$division_id)->where('round_id',$round_id)->where('judge_id',$judge_id)->get();
			
			//$round = Round::with(['division','division.competition','division.choirs'])->find($round_id);
			
			$round = Round::with(['division','division.competition','division.choirs','division.judges' => function($query) use ($judge_id) {
					$query->where('judge_id',$judge_id)->first();
				}, 'division.judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}, 'division.judges.captions.criteria','division.choirs'])->find($round_id);
				
			$judge = Judge::find($judge_id);
			
			$captions = Caption::get();
			
			return view('competition_division_round_judge.judge.show',compact('rawScores','captions','round','judge'));
		}
		
		
		
		public function show_ranked($competition,$division_id,$round_id,$judge_id)
		{
			
			$scoreboard = new Scoreboard([
				'division_id' => $division_id,
				'round_id' => $round_id,
				'judge_id' => $judge_id
			]);

			$rankedScores = $scoreboard->ranked_scores();
			
			//$round = Round::with('division','division.competition')->find($round_id);
			
			$round = Round::with(['division','division.competition','division.choirs','division.judges' => function($query) use ($judge_id) {
					$query->where('judge_id',$judge_id)->first();
				}, 'division.judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}, 'division.judges.captions.criteria','division.choirs'])->find($round_id);
				
			$judge = Judge::find($judge_id);
			
			$captions = Caption::get();
			
			return view('competition_division_round_judge.judge.show_ranked',compact('rankedScores','captions','round','judge'));
		}
		
		
}
