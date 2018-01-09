<?php

namespace App\Carmen;

use App\SoloRawScore;

class SoloRankedScores {

  protected $totalScores;
  protected $performers;

  public function __construct($totalScores, $performers)
  {
    $this->totalScores = $totalScores;
    $this->performers = $performers;
  }

  public function get()
  {
    $rankedScores = $this->totalScores->whereIn('performer_id', $this->performers->pluck('id')->toArray())->sortByDesc('score');

    $loop = 1;
    $currentRank = 1;
    $previousScore = null;

    $rankedScores = $rankedScores->transform(function($item, $key) use (&$loop, &$currentRank,
      &$previousScore) {

      if ($item['score'] != $previousScore) {
        $currentRank = $loop;
      }

      $item['rank'] = $currentRank;
      $previousScore = $item['score'];
      $loop++;
      return $item;

    });

    return $rankedScores;
  }
}
