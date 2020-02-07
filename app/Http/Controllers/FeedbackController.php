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
      $comment_recipient_id = $commentUrl->recipient_id;


      $comments = Comment::with(['judge'])->where('choir_id', $comment_recipient_id)->get();
      //dd($comments);
      $recordings = Recording::where('choir_id', $comment_recipient_id)->whereIn('division_id', $commentUrl->competition->divisions->pluck('id')->toArray())->get();
      //dd($recordings);
      return view('feedback.show', ['comments' => $comments, 'recordings' => $recordings, 'competition' => $commentUrl->competition, 'choir' => $choir, 'comment_recipient_id' => $comment_recipient_id]);
    }
}
