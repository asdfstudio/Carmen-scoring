<?php

namespace App\Http\Controllers\Judge;

use Auth;
use App\Round;
use App\Comment;
use App\Http\Requests;
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

        $round = Round::with(['competition' => function($query) {
            $query->withoutGlobalScope('organization');
        }])->find($round_id);
        $competition = $round->competition;

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

        event(new CommentSaved($comment, $competition));

        return response()->json($comment);
    }
}
