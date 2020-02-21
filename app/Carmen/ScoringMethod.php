<?php

namespace App\Carmen;

use App\RawScore;
use App\Division;
use App\Competition;

class ScoringMethod {

  protected $weightedScores;
  protected $penalties;
  protected $judges = [];
  protected $choirs = [];
  protected $captions = [];
  protected $calculated_scores = [];
  protected $ranked = [];
  protected $total_ranked = [];
  protected $totaled = [];
  protected $totals = [];
  protected $skip_epoch = '2020-02-06';
  protected $is_the_skip_epoch;


  public function __construct($weightedScores, $penalties = false)
  {
    if($weightedScores->count()){
      $competition = Division::with('competition')->find($weightedScores->first()->division_id)->competition;
    } else {
      $route_params = \Route::current()->parameters();
      $competition = Competition::find($route_params['competition']);
    }
    if(isset($_GET['skip_ranks'])){
      $this->is_the_skip_epoch = boolval(intval($_GET['skip_ranks']));
    } else {
      $this->is_the_skip_epoch = empty($competition) || empty($competition->begin_date) || $competition->begin_date >= $this->skip_epoch;
    }
    $this->weightedScores = $weightedScores;
    $this->penalties = $penalties;
    $this->judges = $this->weightedScores->unique('judge_id')->pluck('judge_id');
    $this->choirs = $this->weightedScores->unique('choir_id')->pluck('choir_id');
    $this->captions = $this->weightedScores->unique('criterion_caption_id')->pluck('criterion_caption_id');
  }


  public function weighted_scores()
  {
    return $this->weightedScores;
  }


  public function total_raw_rank($caption_id = false)
  {
    return $this->calculate_rank('score', $caption_id);
  }


  public function total_weighted_rank($caption_id = false)
  {
    return $this->calculate_rank('weightedScore', $caption_id);
  }


  public function calculate_rank($scoreField = 'score', $caption_id = false)
  {
    $captionRank = collect();

    $this->choirs->each(function($choir_id, $key) use ($caption_id, $captionRank, $scoreField){

      $query = $this->weightedScores->where('choir_id', $choir_id);

      if($caption_id)
        $query = $query->where('criterion_caption_id', $caption_id);

      $score = $query->sum($scoreField);

      // Subtract any penalties from the score
      if($this->penalties AND !$caption_id)
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
    return $this->assign_rank_skippy($sorted);
  }


  public function total_rank($caption_id = false)
  {
    $key = $caption_id ? $caption_id : 0;
    if(array_key_exists($key, $this->total_ranked)){
      return $this->total_ranked[$key];
    }

    $captionRank = collect();

    $this->choirs->each(function($choir_id, $key) use ($caption_id, $captionRank){
      $score = $this->total($choir_id, $caption_id);
      $captionRank->put($choir_id,['choir_id' => $choir_id, 'score' => $score]);
    });

    // Sort
    $sorted = $captionRank->sortBy('score');

    // Assign rank and return
    $rank = $this->assign_rank_skippy($sorted);

    return $this->total_ranked[$key] = $rank;
  }


  public function total($choir_id, $caption_id = false)
  {
    $key = $choir_id.'x'.$caption_id;
    if(array_key_exists($key, $this->totaled)){
      return $this->totaled[$key];
    }

    $total = 0;

    $this->judges->each(function($judge_id, $key) use ($choir_id, $caption_id, &$total) {
      $rank = $this->rank($judge_id, $caption_id)->where('choir_id', $choir_id)->pluck('rank')->first();
      $total = $total + $rank;
    });

    $this->totaled[$key] = $total;
    return $total;
  }


  public function rank($judge_id = false, $caption_id = false)
  {
    $key = $judge_id."x".$caption_id;
    if(array_key_exists($key, $this->ranked)){
      return $this->ranked[$key];
    }

    // Caclculate total scores
    $scores = $this->calculate_scores($judge_id, $caption_id);

    // Sort by sum descending
    $sorted = $scores->sortByDesc('score');

    return $this->ranked[$key] = $this->assign_rank($sorted);
  }


  protected function calculate_scores($judge_id, $caption_id, $scoreField = 'weightedScore')
  {
    $key = $judge_id."x".$caption_id;
    if(array_key_exists($key, $this->calculated_scores)){
      return $this->calculated_scores[$key];
    }

    // Create new $scores collection
    $scores = collect();

    // Loop through choirs and compare sums
    $this->choirs->each(function($choir_id, $key) use ($judge_id, $caption_id, $scores, $scoreField){
      // Get the sum of the weighted scores for each choir
      $query = $this->weightedScores->where('choir_id', $choir_id);

      // Filter by judge
      if($judge_id)
        $query = $query->where('judge_id', $judge_id);

      // Filter by caption
      if($caption_id)
        $query = $query->where('criterion_caption_id', $caption_id);

      // Get the sum
      $score = $query->sum($scoreField);

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
      if($score){
        $scores->put($choir_id, ['choir_id' => $choir_id, 'score' => $score]);
      }
    });

    return $this->calculated_scores[$key] = $scores;
  }


  protected function assign_rank($sortedTotals, $force_skippy = false)
  {
    // Assign number rank
    $loops = 1;
    $previous_rank = 1;
    $previous_score = false;
    $tied_ranks = [];

    $rank = $sortedTotals->map(function($item) use (&$loops, &$previous_rank,  &$previous_score, &$tied_ranks, $force_skippy) {

      if($item['score'] == $previous_score){
        $item['rank'] = $previous_rank;
        $tied_ranks[] = $item['rank'];
      } else {
        $item['rank'] = $loops;
        $previous_rank = $loops;
      }

      $previous_score = $item['score'];

      // The skip epoch ensures that all competitions after a certain point will use the
      // skippy method, while leaving historical scores unaffected.
      if($this->is_the_skip_epoch || $force_skippy){
        $loops++;
      } else {
        $loops = $previous_rank + 1;
      }

      return $item;
    });

    // Go back through and flag any results that are a tie.
    $rank = $rank->map(function($item) use ($tied_ranks) {

      if(in_array($item['rank'], $tied_ranks)){
        $item['tied'] = 1;
      } else {
        $item['tied'] = 0;
      }

      return $item;
    });

    return $rank;
  }


  protected function assign_rank_skippy($sortedTotals)
  {
    // The second argument forces the skippy method.
    return $this->assign_rank($sortedTotals, true);
  }


}
