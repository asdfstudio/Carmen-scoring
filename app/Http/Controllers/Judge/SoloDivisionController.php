<?php

namespace App\Http\Controllers\Judge;

use Auth;
use Illuminate\Http\Request;
use App\Competition;
use App\SoloDivision;
use App\SoloRawScore;
use App\Http\Requests;
use App\Carmen\SoloTotalScores;
use App\Carmen\SoloRankedScores;
use App\Http\Controllers\Controller;

class SoloDivisionController extends Controller
{

  /**
   * [show description]
   * @param  Competition  $competition  [description]
   * @param  SoloDivision $soloDivision [description]
   * @return [type]                     [description]
   */
  public function show(Competition $competition, SoloDivision $soloDivision)
  {
    $rawScores = SoloRawScore::where('solo_division_id', $soloDivision->id)
                                  ->where('judge_id', Auth::user()->person_id)
                                  ->get();

    $soloDivision->load('performers');

    $soloDivision->performers->transform(function($performer, $key) use ($rawScores) {
      $performer->score = $rawScores->where('performer_id', $performer->id)->sum('score');
      return $performer;
    });

    return view('solo-division.judge.show', compact('competition', 'soloDivision', 'rawScores'));
  }

  /**
   * [results description]
   * @param  Competition  $competition  [description]
   * @param  SoloDivision $soloDivision [description]
   * @param  [type]       $gender       [description]
   * @return [type]                     [description]
   */
  public function results(Competition $competition, SoloDivision $soloDivision, Request $request)
  {
    $category = $request->input('category');

    $soloDivision->load('performers', 'judges');

    if ($category) {
      $soloDivision->performers = $soloDivision->performers->where('category', $category);

      if ($category == $soloDivision->category_1) {
        $categoryName = $soloDivision->category_1;
      } elseif ($category == $soloDivision->category_2) {
        $categoryName = $soloDivision->category_2;
      } else {
        $categoryName = false;
      }

    } else {
      $categoryName = false;
    }

    $rawScores = SoloRawScore::where('solo_division_id', $soloDivision->id)
                                  //->where('judge_id', Auth::user()->person_id)
                                  ->get();

    $totalScores = (new SoloTotalScores($rawScores , $soloDivision->performers))->get();
    $rankedScores = (new SoloRankedScores($totalScores , $soloDivision->performers))->get();

    $judges = $soloDivision->judges;

    $soloDivision->performers->transform(function($performer, $key) use ($rawScores, $rankedScores, $judges) {
      $performer->rank = $rankedScores->where('performer_id', $performer->id)->pluck('rank')->first();
      $performer->score = $rawScores->where('performer_id', $performer->id)->sum('score');

      $judgeScores = [];

      foreach ($judges as $judge) {
        $judgeScores[$judge->id] = $rawScores->where('performer_id', $performer->id)->where('judge_id', $judge->id)->sum('score');
      }

      $performer->judgeScores = $judgeScores;

      return $performer;
    });

    $soloDivision->performers = $soloDivision->performers->sortBy('rank');

    return view('solo-division.judge.results', compact('competition', 'soloDivision', 'rawScores', 'rankedScores', 'totalScores', 'categoryName'));
  }

}
