<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\CommentUrl;
use App\Comment;

class FeedbackController extends Controller
{
    public function show($accessCode = false)
    {
      $accessCode = strtolower($accessCode);

      if(!$accessCode)
      {
        return view('feedback.guest', ['message' => 'Please enter an access token to view comments from judges.']);
      }

      $commentUrl = CommentUrl::with(['choir', 'choir.school', 'competition', 'competition.divisions', 'competition.divisions.rounds'])->where('access_code', $accessCode)->first();

      if(!$commentUrl)
      {
        return view('feedback.guest', ['message' => 'The access token you specified is not valid.']);
      }

      $comments = Comment::with(['judge'])->where('choir_id', $commentUrl->choir_id)->get();

      return view('feedback.show', ['comments' => $comments, 'competition' => $commentUrl->competition, 'choir' => $commentUrl->choir]);
    }
}
