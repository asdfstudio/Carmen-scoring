<?php

namespace App\Carmen;

use App\RawScore;

class RankedScores {
  protected $weightedScores;
  protected $penalties;
  //protected $choirId;
  //protected $judgeId;
  //protected $captionId;

  protected $judges = [];
  protected $choirs = [];

  protected $calculated_scores = [];
  protected $ranked = [];
  protected $total_ranked = [];
  protected $totaled = [];

  public function __construct($weightedScores, $penalties = false)
  {
    $this->weightedScores = $weightedScores;
    $this->penalties = $penalties;

    $this->judges = $this->weightedScores->unique('judge_id')->pluck('judge_id');
    $this->choirs = $this->weightedScores->unique('choir_id')->pluck('choir_id');
    //return $this->weightedScores;
  }

  public function total_raw_rank($caption_id = false)
  {
    //echo 'total_raw_rank<br />';
    return $this->calculate_rank('score', $caption_id);
  }

  public function total_weighted_rank($caption_id = false)
  {
    //echo 'total_weighted_rank<br />';
    return $this->calculate_rank('weightedScore', $caption_id);
  }

  public function calculate_rank($scoreField = 'score', $caption_id = false)
  {
    $captionRank = collect();

    //echo 'calculate_rank<br />';

    $this->choirs->each(function($choir_id, $key) use ($caption_id, $captionRank, $scoreField){
      //echo 'calculate_rank-loop<br />';

      $query = $this->weightedScores->where('choir_id', $choir_id);

      if($caption_id)
        $query = $query->where('criterion.caption_id', $caption_id);

      $score = $query->sum($scoreField);

      // Subtract any penalties from the score
      if($this->penalties)
      {
        $choir_penalties = $this->penalties->where('choir_id', $choir_id);

        if(!$choir_penalties->isEmpty())
        {
          // Get all overall penalties
          $overall_penalty_amount = $choir_penalties->where('apply_per_judge', 0)->sum('amount');
          $score = $score - $overall_penalty_amount;

          // Get all judge penalties
          $judge_penalty_amount = $this->judges->count() * $choir_penalties->where('apply_per_judge', 1)->sum('amount');
          $score = $score - $judge_penalty_amount;
        }

      }

      $captionRank->put($choir_id,['choir_id' => $choir_id, 'score' => $score]);
    });

    // Sort
    $sorted = $captionRank->sortByDesc('score');

    // Assign rank and return
    return $rank = $this->assign_rank($sorted);
  }


  public function total_rank($caption_id = false)
  {
    $key = $caption_id ? $caption_id : 0;
    if(array_key_exists($key, $this->total_ranked))
    {
      //echo "use_pretotalranked<br />";
      //dd($this->ranked[$judge_id."x".$caption_id]);
      return $this->total_ranked[$key];
    }

    $captionRank = collect();

    //echo 'total_rank<br />';

    $this->choirs->each(function($choir_id, $key) use ($caption_id, $captionRank){

      //echo 'total_rank-loop<br />';
      $score = $this->total($choir_id, $caption_id);

      $captionRank->put($choir_id,['choir_id' => $choir_id, 'score' => $score]);
    });

    // Sort
    $sorted = $captionRank->sortBy('score');

    // Assign rank and return
    $rank = $this->assign_rank($sorted);
    $this->total_ranked[$key] = $rank;
    return $rank;
  }

  public function total($choir_id, $caption_id = false)
  {
    $key = $choir_id.'x'.$caption_id;
    if(array_key_exists($key, $this->totaled))
    {
      //echo "use_pretotaled-$key<br />";
      return $this->totaled[$key];
    }

    $total = 0;

    //echo 'total<br />';

    $this->judges->each(function($judge_id, $key) use ($choir_id, $caption_id, &$total) {
      //echo 'total-loop<br />';
      $rank = $this->rank($judge_id, $caption_id)->where('choir_id', $choir_id)->pluck('rank')->first();
      $total = $total + $rank;
    });

    // Subtract any penalties from the score
    /*if($this->penalties)
    {
      $choir_penalties = $this->penalties->where('choir_id', $choir_id);

      if(!$choir_penalties->isEmpty())
      {
        // Get all judge penalties
        $overall_penalty_amount = $choir_penalties->where('apply_per_judge', 1)->sum('amount');
        $total = $total - $overall_penalty_amount;
      }

    }*/
    $this->totaled[$key] = $total;
    return $total;
  }

  public function rank($judge_id = false, $caption_id = false)
  {
    if(array_key_exists($judge_id."x".$caption_id, $this->ranked))
    {
      //echo "use_preranked<br />";
      //dd($this->ranked[$judge_id."x".$caption_id]);
      return $this->ranked[$judge_id."x".$caption_id];
    }

    //echo 'rank-'.$judge_id.'x'.$caption_id.'<br />';
    // Caclculate total scores
    $scores = $this->calculate_scores($judge_id, $caption_id);

    // Sort by sum descending
    $sorted = $scores->sortByDesc('score');

    return $rank = $this->assign_rank($sorted, $judge_id.'x'.$caption_id);
  }


  protected function calculate_scores($judge_id, $caption_id)
  {
    if(array_key_exists($judge_id."x".$caption_id, $this->calculated_scores))
    {
      //echo "use_precalculated_scores<br />";
      return $this->calculated_scores[$judge_id."x".$caption_id];
    }

    // Create new $scores collection
    $scores = collect();

    //echo "calculate_scores-".$judge_id."x".$caption_id."<br />";

    // Loop through choirs and compare sums
    $this->choirs->each(function($choir_id, $key) use ($judge_id, $caption_id, $scores){
      //echo 'calculate_scores-loop<br />';
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

      // Subtract any penalties from the score
      if($this->penalties AND $caption_id == false)
      {
        $choir_penalties = $this->penalties->where('choir_id', $choir_id);

        if(!$choir_penalties->isEmpty())
        {
          // Get all judge penalties
          $per_judge_penalty_amount = $choir_penalties->where('apply_per_judge', 1)->sum('amount');
          $score = $score - $per_judge_penalty_amount;
        }

      }

      // Add the choir and score to the $scores collection
      if($score)
      {
        $scores->put($choir_id, ['choir_id' => $choir_id, 'score' => $score]);
      }

    });

    $this->calculated_scores[$judge_id."x".$caption_id] = $scores;

    return $scores;
  }


  protected function assign_rank($sortedTotals, $key = false)
  {
    // Assign number rank
    $loops = 1;
    $previous_rank = 1;
    $previous_score = false;

    //echo 'assign_rank<br />';

    $rank = $sortedTotals->map(function($item, $key) use (&$loops, &$previous_rank,  &$previous_score) {

      //echo 'assign_rank-loop<br />';

      if($item['score'] == $previous_score)
      {
        $item['rank'] = $previous_rank;
      }
      else {
        $item['rank'] = $loops;
        $previous_rank = $loops;
      }

      $previous_score = $item['score'];
      $loops++;

      return $item;
    });

    if($key)
    {
      $this->ranked[$key] = $rank;
    }

    return $rank;
  }

}
