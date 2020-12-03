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

      // Get the divisions that this choir is in
      $division_ids = DB::Table('choir_division AS cd')
          ->select('division_id')
          ->join('divisions AS d', 'd.id', '=', 'cd.division_id')
          ->join('rounds AS r', 'r.id', '=', 'd.round_id')
          ->join('competitions AS c', 'r.competition_id', '=', 'c.id')
          ->where('c.id', $competition->id)
          ->where('cd.choir_id', $choir->id)
          ->get()->pluck('division_id')
          ;

      $divisions = $competition->divisions->filter(function($value) use ($division_ids) {
          return $division_ids->contains($value->id);
      });

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

      return view('feedback.show', ['comments' => $comments, 'recordings' => $recordings, 'competition' => $competition, 'divisions' => $divisions, 'choir' => $choir]);
    }
}
