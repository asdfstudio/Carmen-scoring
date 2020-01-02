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
use App\Comment;

use App\Carmen\Scorekeeper;
use App\Carmen\Scoreboard;

use Event;
use App\Events\CommentSaved;

use Auth;

class CompetitionDivisionRoundChoirController extends Controller
{
		public function index($competition,$division_id,$round_id,$choir_id)
		{

			$rawScores = RawScore::with('judge','choir')->where('division_id',$division_id)->where('round_id',$round_id)->where('choir_id',$choir_id)->get();

			$round = Round::with(['division','division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      } ,'division.choirs' => function($query) use ($choir_id) {
				$query->where('choir_id',$choir_id);
			}, 'division.judges' => function ($query) {
					$query->groupBy('judge_id');
				}])->find($round_id);

			$choir = Choir::find($choir_id);

			$captions = Caption::forSheet($round->division->sheet);

			return view('competition_division_round_choir_judge.judge.index',compact('rawScores','captions','round','choir'));
		}


    public function show($competition_id,$division_id,$round_id,$choir_id)
		{

			$judge_id = Auth::user()->person_id;
			$judge = Judge::find($judge_id);

			$rawScores = RawScore::with('judge','choir','criterion')
				->where('division_id',$division_id)
				->where('round_id',$round_id)
				->where('judge_id',$judge_id)
				->where('choir_id',$choir_id)
				->get();

			$scoreboard = new Scoreboard(['round_id' => $round_id]);

	    $rawScores = $scoreboard->rawScores;
	    $weightedScores = $scoreboard->weightedScores;
      $rankedScores = $scoreboard->rankedScoresForCurrentMethod;

			$comment = Comment::where('judge_id', $judge_id)
									->where('choir_id', $choir_id)
									->where('subject_type', 'App\Round')
									->where('subject_id', $round_id)
									->pluck('comments')->first();

			//dd($rawScores);

			//$division = Division::with('choirs','judges','judges.captions','competition','competition.organization')->find($division_id);

			/*$round = Round::with(['division','division.competition','division.choirs' => function($query) use ($choir_id) {
				$query->where('choir_id',$choir_id);
			}, 'division.judges' => function ($query) use ($judge_id) {
					$query->where('judge_id',$judge_id);
				}])->find($round_id);*/


			$round = Round::with(['division', 'division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      }, 'division.sheet', 'division.sheet.criteria', 'division.judges' => 	function($query) use ($judge_id) {
					$query->where('judge_id',$judge_id)->first();
				}, 'division.judges.captions' => function($query) use ($division_id) {
					$query->where('division_id',$division_id);
				}, 'division.judges.captions.criteria','division.choirs' => function($query) use ($choir_id) {
				$query->where('choir_id',$choir_id);
			}])->find($round_id);

			$choir = Choir::find($choir_id);

			//$captions = Caption::get();
			//dd($rawScores);

			//$competition = Competition::find($competition_id);
			//$division = $round->division;

			$division = $round->division;
      $competition = $division->competition;

			//$captions = $division->judges->first()->captions;
      $captions = Caption::forSheet($division->sheet);
      if(!empty($division->judges->first())){
        $judgeCaptionIds = $division->judges->first()->captions->pluck('id')->toArray();
        $captions = $captions->whereIn('id', $judgeCaptionIds);
      }

			return view('competition_division_round_choir.judge.show',compact('scoreboard', 'rawScores', 'weightedScores', 'rankedScores', 'captions','round','choir','judge','competition','division', 'comment'));
		}



		public function save_scores($competition_id, $division_id, $round_id, $choir_id, Request $request)
		{
			// Check that we can do this
			$competition = Competition::withoutGlobalScope('organization')->find($competition_id);

			$judge_id = Auth::user()->person_id;

			$scorekeeper = new Scorekeeper([
				'division_id' => $division_id,
				'round_id' => $round_id,
				'choir_id' => $choir_id,
				'judge_id' => $judge_id
			]);

			$criterion_id = $request->input('criterion_id', NULL);
			$score = $request->input('score', NULL);
			$scores = $request->input('scores', NULL);

			// Save comments
			$comment = Comment::firstOrNew([
				'judge_id' => $judge_id,
				'choir_id' => $choir_id,
				'recipient_type' => 'App\Choir',
				'recipient_id' => $choir_id,
				'subject_type' => 'App\Round',
				'subject_id' => $round_id
			]);
			$comment->comments = $request->input('comment');
			$comment->save();

			Event::fire(new CommentSaved($comment, $competition));


			// Save a single score
			if($criterion_id AND $score)
			{
				$response = $scorekeeper->criterion($criterion_id)->score($score)->save();
			}


			// Save multiple scores
			if($scores)
			{
				$response = $scorekeeper->save_multiple_scores($scores);
			}

			// Redirect
			if($request->input('save_go'))
			{
				return redirect()->route('judge.round.scores.summary', [$competition_id, $division_id, $round_id])->with('success','Scores saved!');
			}
			else {
				return redirect()->route('judge.competition.division.round.choir.show', [$competition_id, $division_id, $round_id, $choir_id])->with('success','Scores saved!');
			}
		}
}
