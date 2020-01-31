<?php

namespace App\Http\Controllers\organizer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Competition;
use App\CommentUrl;

class CompetitionFeedbackUrlController extends Controller
{
    public function index($competition_id)
    {
      $competition = Competition::with('organization', 'place', 'commentUrls')->find($competition_id);
      
      return view('competition_division_feedback_url.organizer.index', compact('competition'));
    }
}
