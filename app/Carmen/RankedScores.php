<?php

namespace App\Carmen;

use App\RawScore;

class RankedScores {
  protected $weightedScores;
  //protected $choirId;
  //protected $judgeId;
  //protected $captionId;

  protected $judges = [];
  protected $choirs = [];

  public function __construct($weightedScores)
  {
    $this->weightedScores = $weightedScores;

    $this->judges = $this->weightedScores->unique('judge_id')->pluck('judge_id');
    $this->choirs = $this->weightedScores->unique('choir_id')->pluck('choir_id');
    //return $this->weightedScores;
  }


  public function total_rank($caption_id = false)
  {
    $captionRank = collect();

    $this->choirs->each(function($choir_id, $key) use ($caption_id, $captionRank){

      $score = $this->total($choir_id, $caption_id);

      $captionRank->put($choir_id,['choir_id' => $choir_id, 'score' => $score]);
    });

    // Sort
    $sorted = $captionRank->sortBy('score');

    // Assign rank and return
    return $rank = $this->assign_rank($sorted);
  }

  public function total($choir_id, $caption_id = false)
  {
    $total = 0;

    $this->judges->each(function($judge_id, $key) use ($choir_id, $caption_id, &$total) {
      $rank = $this->rank($judge_id, $caption_id)->where('choir_id', $choir_id)->pluck('rank')->first();
      $total = $total + $rank;
    });

    return $total;
  }

  public function rank($judge_id = false, $caption_id = false)
  {
    // Caclculate total scores
    $scores = $this->calculate_scores($judge_id, $caption_id);

    // Sort by sum descending
    $sorted = $scores->sortByDesc('score');

    return $rank = $this->assign_rank($sorted);
  }


  protected function calculate_scores($judge_id, $caption_id)
  {
    // Create new $scores collection
    $scores = collect();

    // Loop through choirs and compare sums
    $this->choirs->each(function($choir_id, $key) use ($judge_id, $caption_id, $scores){

      // Get the sum of the weighted scores for each choir
      $query = $this->weightedScores->where('choir_id', $choir_id);

      // Filter by judge
      if($judge_id)
        $query = $query->where('judge_id', $judge_id);

      // Filter by caption
      if($caption_id)
        $query = $query->where('criterion.caption_id', $caption_id);

      // Get the sum
      $score = $query->sum('weightedScore');

      // Add the choir and score to the $scores collection
      $scores->put($choir_id, ['choir_id' => $choir_id, 'score' => $score]);
    });

    return $scores;
  }


  protected function assign_rank($sortedTotals)
  {
    // Assign number rank
    $loops = 1;
    $previous_rank = 1;
    $previous_score = false;

    $rank = $sortedTotals->map(function($item, $key) use (&$loops, &$previous_rank,  &$previous_score) {

      if($item['score'] == $previous_score)
      {
        $item['rank'] = $previous_rank;
      }
      else {
        $item['rank'] = $loops;
        $previous_rank = $loops;
      }

      $loops++;

      return $item;
    });

    return $rank;
  }
}
