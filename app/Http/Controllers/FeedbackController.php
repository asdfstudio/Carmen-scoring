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

      $commentUrl = CommentUrl::with(['recipient', 'choir', 'competition', 'competition.divisions' => function($q) {
        $q->withoutGlobalScope('organization');
      }, 'competition.divisions.rounds', 'competition.soloDivisions'])->where('access_code', $accessCode)->first();

      if(!$commentUrl)
      {
        return view('feedback.guest', ['message' => 'The access token you specified is not valid.']);
      }

      /*if ($commentUrl->recipient_type == 'App\Choir') {
        $commentUrl->load('recipient.school');
        $choir = $commentUrl->recipient;
      }

      if ($commentUrl->recipient_type == 'App\Performer') {
        $commentUrl->load('recipient.choir', 'recipient.choir.school');
        $performer = $commentUrl->recipient;
        $choir = $performer->choir;
      }*/

      $choir = $commentUrl->choir;


      $comments = Comment::with(['judge'])->where('choir_id', $commentUrl->recipient_id)->get();

      return view('feedback.show', ['comments' => $comments, 'competition' => $commentUrl->competition, 'choir' => $choir]);
    }
}
