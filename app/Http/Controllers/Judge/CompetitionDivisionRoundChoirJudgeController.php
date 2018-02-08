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

use Auth;

class CompetitionDivisionRoundChoirJudgeController extends Controller
{
    public function index($competition,$division_id,$round_id,$choir_id)
		{

			//$judge_id = Auth::user()->person_id;

			$rawScores = RawScore::with('judge','choir')->where('division_id',$division_id)->where('round_id',$round_id)->where('choir_id',$choir_id)->get();

			$round = Round::with(['division','division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      },'division.choirs' => function($query) use ($choir_id) {
				$query->where('choir_id',$choir_id);
			}, 'division.judges' => function ($query) {
					$query->groupBy('judge_id');
				}])->find($round_id);

			$choir = Choir::find($choir_id);
			//$judge = Judge::find($judge_id);

			$captions = Caption::forSheet($round->division->sheet);

			return view('competition_division_round_choir_judge.judge.index',compact('rawScores','captions','round','choir'));
		}


		public function show($competition,$division_id,$round_id,$choir_id,$judge_id)
		{
			//$judge_id = Auth::user()->person_id;

			$rawScores = RawScore::with('judge','choir')->where('division_id',$division_id)->where('round_id',$round_id)->where('judge_id',$judge_id)->where('choir_id',$choir_id)->get();

			//dd($rawScores);

			//$division = Division::with('choirs','judges','judges.captions','competition','competition.organization')->find($division_id);

			$round = Round::with(['division','division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      },'division.choirs' => function($query) use ($choir_id) {
				$query->where('choir_id',$choir_id);
			}])->find($round_id);


			$round = Round::with(['division','division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      },'division.judges' => function($query) use ($judge_id) {
					$query->where('judge_id',$judge_id)->first();
				}, 'division.judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}, 'division.judges.captions.criteria','division.choirs' => function($query) use ($choir_id) {
				$query->where('choir_id',$choir_id);
			}])->find($round_id);

			$choir = Choir::find($choir_id);
			$judge = Judge::find($judge_id);

			$captions = Caption::forSheet($round->division->sheet);

			return view('competition_division_round_choir_judge.judge.show',compact('rawScores','captions','round','choir','judge'));
		}

}
