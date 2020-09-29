<?php

namespace App\Http\Controllers;

use App\Audience;
use App\Competition;
use App\SoloDivision;
use App\Vote;
use Illuminate\Http\Request;
use App\Division;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
//        $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('home');
  }

  /**
   * @param $organizer
   * @param $alias
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
   */
  public function organizer($organizer, $alias)
  {
    $tmp = explode('-', $organizer);
    $divisionId = end($tmp);
    $division = Division::with('competition', 'choirs')->find($divisionId);
    $audience = $division->audience;
    $colors = ['Red', 'Green', 'Orange', 'Purple', 'Blue', 'Black'];
    $view = 'votes.bright';

    if (isset($audience) && 1 === $audience->is_dark) {
      $view = 'votes.dark';
    }
    return view($view, compact('division','audience', 'colors'));
  }

  /**
   * @param $organizer
   * @param $alias
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
   */
  public function soloDivisionVote($organizer, $alias) {
    $tmp = explode('-', $organizer);
    $divisionId = end($tmp);
    $division = SoloDivision::with( 'competition','performers')->find($divisionId);
    $competition = $division->competition;
    $audience = Audience::where('division_id', $divisionId)
      ->where('competition_id', $competition->id)
      ->first();
    $colors = ['Red', 'Green', 'Orange', 'Purple', 'Blue', 'Black'];
    $view = 'votes.solo.bright';
    if (isset($audience) && 1 === $audience->is_dark) {
      $view = 'votes.solo.dark';
    }
    return view($view, compact('division','audience', 'colors'));
  }
}
