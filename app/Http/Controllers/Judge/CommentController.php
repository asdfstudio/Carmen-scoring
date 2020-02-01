<?php

namespace App\Http\Controllers\Judge;

use Auth;
use Event;
use App\Judge;
use App\Choir;
use App\Round;
use App\Comment;
use App\Caption;
use App\RawScore;
use App\Division;
use App\Competition;
use App\Http\Requests;
use App\Carmen\Scoreboard;
use App\Carmen\Scorekeeper;
use App\Events\CommentSaved;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{


		public function save(Request $request)
		{
			$judge_id = Auth::user()->person_id;
			$round_id = $request->input('round_id', NULL);
			$choir_id = $request->input('choir_id', NULL);

			$round = Round::with(['division.competition' => function($query) {
        $query->withoutGlobalScope('organization');
      }])->find($round_id);
			$competition = $round->division->competition;

			//dd([$judge_id, $round_id, $choir_id]);

			// Save comments
			$comment = Comment::firstOrNew([
				'judge_id' => $judge_id,
				'choir_id' => $choir_id,
				'recipient_type' => 'App\Choir',
				'recipient_id' => $choir_id,
				'subject_type' => 'App\Round',
				'subject_id' => $round_id
			]);
      
      $comment_text = $request->input('comment');
      
      $comment->comments = $request->input('comment');
			$comment->save();

			Event::fire(new CommentSaved($comment, $competition));

			return response()->json($comment);
		}
}
