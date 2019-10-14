<?php

namespace App\Carmen;

use DB;
use App\Round;
use App\RawScore;

class Ratings
{
  protected $round;

  public function __construct(Round $round)
  {
    $this->round = $round;
  }

  public function all($keys = null)
  {
    $scores = $this->getScores();
    //dd($scores);

    $percentages = $this->calculatePercentages($scores);
    //dd($percentages);

    $ratings = $this->calculateRatings($percentages);

    return $ratings;
  }

  public function calculatePercentages($scores)
  {
    $percentages = [];

    foreach ($this->round->choirs as $choir) {
      $earnedScore = $scores->where('choir_id', $choir->id)->sum('earned_score');
      $maxScore = $scores->where('choir_id', $choir->id)->sum('max_score');

      if (!$maxScore) {
        $earnedPercent = 0;
      } else {
        $earnedPercent = $earnedScore / $maxScore;
      }


      $percentages[] = [
        'choir' => $choir,
        'earned_percent' => $earnedPercent,
        'earned_score' => $earnedScore,
        'max_score' => $maxScore
      ];
    }

    return $percentages;
  }

  public function calculateRatings($percentages)
  {
    $ratings = [];

    $ratingOptions = collect($this->round->division->rating_system);
    $ratingOptions = $ratingOptions->sortByDesc('min_score')->toArray();


    foreach ($percentages as $index => $percentage) {

      $wholeEarnedPercent = $percentage['earned_percent'] * 100;

      $ratings[$index] = [
        'choir' => $percentage['choir'],
        'rating' => false,
        'earned_score' => $wholeEarnedPercent
      ];

      foreach ($ratingOptions as $ratingOption) {

        if ($wholeEarnedPercent >= $ratingOption['min_score']) {
          $ratings[$index]['rating'] = $ratingOption;
          continue 2;
        }
      }
    }

    return collect($ratings);
  }


  public function getScores()
  {
    $scores = DB::select("SELECT choir_id, criteria.caption_id as caption_id,
        IF(criteria.caption_id = 1, sum(score * 1.5), sum(score)) as earned_score,
        IF(criteria.caption_id = 1, sum(criteria.max_score * 1.5), sum(criteria.max_score)) as max_score
      FROM raw_scores
      LEFT JOIN criteria ON criteria.id = criterion_id
      WHERE round_id = ?
      GROUP BY choir_id, caption_id",
      [$this->round->id]);

    return collect($scores);
  }

}
