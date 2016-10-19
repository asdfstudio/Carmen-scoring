<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\RawScore;
use App\Judge;
use App\Round;
use App\Division;
use App\Competition;

use App\Carmen\WeightedScores;
use App\Carmen\RankedScores;

class CompetitionDivisionRoundJudgeController extends Controller
{
    public function show($competition_id,$division_id,$round_id,$judge_id)
		{
      $competition = Competition::with('divisions')->find($competition_id);
      $divisions = $competition->divisions;

			$judge = Judge::with(['captions' => function($query){
        $query->distinct();
        $query->orderBy('id','ASC');
      }])->find($judge_id);

			$round = Round::find($round_id);
			$division = Division::with(['sheet','sheet.criteria','sheet.criteria.caption','competition','choirs','rounds'])->find($division_id);

      //dd($division->sheet);

      $rounds = $division->rounds;

			$rawScores = RawScore::with('choir','judge','criterion','criterion.caption')->where('division_id',$division_id)->where('round_id',$round_id)->where('judge_id',$judge_id)->get();

      $weightedScoresClass = new WeightedScores($rawScores,        $division->caption_weighting_id);
      $weightedScores = $weightedScoresClass->all();

      $rankedScores = new RankedScores($weightedScores);
      //$rankScores = $rankedScores->rank($judge_id);

			return view('competition_division_round_judge.organizer.show', compact('rawScores', 'weightedScores', 'rankedScores', 'judge', 'round', 'division', 'competition', 'divisions', 'rounds'));
		}
}
