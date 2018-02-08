<?php

namespace App\Http\Controllers\Organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Choir;
use App\Round;
use App\RawScore;
use App\Judge;
use App\Division;
use App\Competition;
use App\Caption;
use App\Comment;

use App\Carmen\WeightedScores;
use App\Carmen\RankedScores;

class CompetitionDivisionRoundChoirJudgeController extends Controller
{
    public function show($competition_id, $division_id, $round_id, $choir_id, $judge_id)
		{
			$choir = Choir::find($choir_id);
			$judge = Judge::find($judge_id);
			$round = Round::find($round_id);
      $competition = Competition::with('divisions')->find($competition_id);

      $divisions = $competition->divisions;

			$division = Division::with('rounds','judges','choirs','sheet','sheet.criteria','sheet.criteria.caption')->find($division_id);

      $rounds = $division->rounds;

      $captions = Caption::forSheet($division->sheet);

      $comment = Comment::where('judge_id', $judge_id)
									->where('choir_id', $choir_id)
									->where('subject_type', 'App\Round')
									->where('subject_id', $round_id)
									->pluck('comments')->first();

			$rawScores = RawScore::where('division_id',$division_id)->where('round_id',$round_id)->where('choir_id',$choir_id)->where('judge_id',$judge_id)->get();

      $weightedScoresClass = new WeightedScores($rawScores,        $division->caption_weighting_id);
      $weightedScores = $weightedScoresClass->all();

      $rankedScores = new RankedScores($weightedScores);

      //dd($rawScores);

			return view('competition_division_round_choir_judge.organizer.show',compact('rawScores', 'weightedScores', 'rankedScores', 'choir', 'judge', 'round', 'division', 'competition', 'rounds', 'divisions', 'captions', 'comment'));
		}
}
