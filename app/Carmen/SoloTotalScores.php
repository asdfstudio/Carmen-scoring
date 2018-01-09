<?php

namespace App\Carmen;

use App\SoloRawScore;

class SoloTotalScores {

  protected $rawScores;
  protected $performers;

  public function __construct($rawScores, $performers)
  {
    $this->rawScores = $rawScores;
    $this->performers = $performers;
  }

  public function get()
  {
    $totalScores = collect();

    foreach ($this->performers as $performer) {
      $data = [
        'performer_id' => $performer->id,
        'score' => $this->rawScores->where('performer_id', $performer->id)->sum('score')
      ];
      $totalScores->push($data);
    }

    return $totalScores;
  }
}
