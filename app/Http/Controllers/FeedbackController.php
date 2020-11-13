<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\CommentUrl;
use App\Comment;
use App\Recording;
use DB;

class FeedbackController extends Controller
{
    public function show($accessCode = false)
    {
      $accessCode = strtolower($accessCode);

      if(!$accessCode)
      {
        return view('feedback.guest', ['message' => 'Please enter an access token to view comments from judges.']);
      }

      $commentUrl = CommentUrl::with(['competition' => function($q) {
        $q->withoutGlobalScope('organization');
      }, 'competition.divisions', 'competition.soloDivisions'])->where('access_code', $accessCode)->first();

      if(!$commentUrl)
      {
        return view('feedback.guest', ['message' => 'The access token you specified is not valid.']);
      }

      $choir = $commentUrl->choir;
      $competition = $commentUrl->competition;
      $comment_recipient_id = $commentUrl->recipient_id;

      // Get all the commments for this choir in Rounds and Solo Divisions
      $comments = Comment::with(['judge'])
          ->where('choir_id', $comment_recipient_id)
          ->where(function($query) use ($competition) {
              $query->where('subject_type', 'App\Round')->whereIn('subject_id', $competition->rounds->pluck('id'))
                  ->orWhere(function($query) use ($competition) {
                      $query->where('subject_type', 'App\SoloDivision')->whereIn('subject_id', $competition->soloDivisions->pluck('id'));
                  });
          })
          ->get();

      // Get all the Division Recordings
      $recordings = Recording::where('choir_id', $comment_recipient_id)->whereIn('division_id', $competition->divisions->pluck('id'))->get();

      return view('feedback.show', ['comments' => $comments, 'recordings' => $recordings, 'competition' => $competition, 'choir' => $choir]);
    }
}
