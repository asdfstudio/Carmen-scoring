<?php

namespace App\Carmen;

use App\RawScore;

class ConsensusOrdinalRankScores {
  protected $weightedScores;
  protected $penalties;

  protected $judges = [];
  protected $choirs = [];

  protected $calculated_scores = [];
  protected $ranked = [];
  protected $total_ranked = [];
  protected $totals = [];
  
  
  public function __construct($weightedScores, $penalties = false)
  {
    $this->weightedScores = $weightedScores;
    $this->penalties = $penalties;

    $this->judges = $this->weightedScores->unique('judge_id')->pluck('judge_id');
    $this->choirs = $this->weightedScores->unique('choir_id')->pluck('choir_id');
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
    return $this->total_rank($caption_id, $scoreField);
  }
  
  
  public function total_rank($caption_id = false, $scoreField = 'weightedScore')
  {
    $key = $caption_id ? $caption_id : 0;
    if(array_key_exists($key, $this->total_ranked)){
      return $this->total_ranked[$key];
    }

    $captionRank = collect();

    $this->choirs->each(function($choir_id, $key) use ($caption_id, $captionRank, $scoreField){
      // Get an array that shows how many times the choir has been given each rank.
      $totals = $this->totals($choir_id, $caption_id, $scoreField);
      
      // Make each rank total a property directly on the choir collection item
      // so that we can use it for sorting.
      $data = ['choir_id' => $choir_id];
      foreach($totals as $rank => $count){
        $data[$rank] = $count;
      }
      $captionRank->put($choir_id, $data);
    });
    
    // Sort
    $rank = $this->sort_and_assign_rank($captionRank);
    
    $this->total_ranked[$key] = $rank;
    return $rank;
  }
  
  
  public function sort_and_assign_rank($choirs_with_ranks)
  {
    $sorted = collect();
    $choirs_in_rank_order = [];
    $choir_count = $choirs_with_ranks->count();
    $rank_to_assign = 1;
    
    for($i = 0; $i < $choir_count; $i++){
      
      $top_choir = $this->get_top_choir($choirs_with_ranks);
      
      foreach($top_choir as $choir_id => $choir){
        $choir['tied'] = count($top_choir) > 1 ? 1 : 0;
        $choir['rank'] = $rank_to_assign;
        $choirs_in_rank_order[$choir_id] = $choir;
        $choirs_with_ranks->forget($choir_id);
      }
      
      $rank_to_assign++;
      
    }
    
    foreach($choirs_in_rank_order as $choir_id => $choir){
      $sorted->put($choir_id, $choir);
    }
    
    //dd($sorted);
    
    return $sorted;
  }
  
  
  public function get_top_choir($choirs, $level = 1){
    
    $choirs = $choirs->sortByDesc($level);
    
    $level_is_set = isset($choirs->first()[$level]);
    $level_is_empty = empty($choirs->first()[$level]);
    
    if($level_is_set && $level_is_empty){
      // This level is worthless because no choir being evaluated has attained it.
      // Try the next level.
      $level++;
      return $this->get_top_choir($choirs, $level);
    }
    
    if(!$level_is_set){
      // We have exceeded the number of levels.  We should only end up here if we are trying
      // to break a tie.  That means that whoever is in this $choirs collection is tied for last.
      return $choirs->toArray();
    }
    
    $highest_value = $choirs->first()[$level];
    $top_choir = $choirs->where($level, $highest_value);
    
    if($top_choir->count() == 1){
      // We have a single top choir at this level, so return that one.
      return $top_choir->toArray();
    }
    
    // If we are still here, then there must be a tie at the given level.  Run this function
    // again on the $top_choir set, evaluating the next level.
    $level++;
    return $this->get_top_choir($top_choir, $level);
    
  }
  
  
  public function total($choir_id, $caption_id = false)
  {
    // This function is not applicable to Consensus Ordinal Rank, but it is
    // retained for compatibility with the shared view output.
    return null;
  }
  
  
  public function totals($choir_id, $caption_id = false, $scoreField = 'weightedScore')
  {
    $key = $choir_id.'x'.$caption_id;
    if(array_key_exists($key, $this->totals)){
      return $this->totals[$key];
    }
    
    $totals = [];
    
    // Build an array that shows how many times the choir has been given each rank.
    $this->judges->each(function($judge_id, $key) use ($choir_id, $caption_id, &$totals, $scoreField) {
      $rank = $this->rank($judge_id, $caption_id, $scoreField)->where('choir_id', $choir_id)->pluck('rank')->first();
      if(!empty($rank)){
        for($i = 1; $i <= count($this->choirs); $i++){
          if(empty($totals[$i])){
            $totals[$i] = 0;
          }
          if($rank <= $i){
            $totals[$i]++;
          }
        }
      }
    });

    $this->totals[$key] = $totals;
    
    return $totals;
  }
  
  
  public function rank($judge_id = false, $caption_id = false, $scoreField = 'weightedScore')
  {
    if(array_key_exists($judge_id."x".$caption_id, $this->ranked)){
      return $this->ranked[$judge_id."x".$caption_id];
    }

    // Caclculate total scores
    $scores = $this->calculate_scores($judge_id, $caption_id, $scoreField);

    // Sort by sum descending
    $sorted = $scores->sortByDesc('score');

    return $rank = $this->assign_rank($sorted, $judge_id.'x'.$caption_id);
  }
  
  
  protected function calculate_scores($judge_id, $caption_id, $scoreField = 'weightedScore')
  {
    if(array_key_exists($judge_id."x".$caption_id, $this->calculated_scores)){
      return $this->calculated_scores[$judge_id."x".$caption_id];
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
    $tied_ranks = [];

    $rank = $sortedTotals->map(function($item, $key) use (&$loops, &$previous_rank,  &$previous_score, &$tied_ranks) {

      if($item['score'] == $previous_score){
        $item['rank'] = $previous_rank;
        $tied_ranks[] = $item['rank'];
      } else {
        $item['rank'] = $loops;
        $previous_rank = $loops;
      }

      $previous_score = $item['score'];
      $loops = $previous_rank + 1;
      
      return $item;
    });
    
    // Go back through and flag any results that are a tie.
    $rank = $rank->map(function($item, $key) use ($tied_ranks) {

      if(in_array($item['rank'], $tied_ranks)){
        $item['tied'] = 1;
      } else {
        $item['tied'] = 0;
      }
      
      return $item;
    });

    if($key){
      $this->ranked[$key] = $rank;
    }

    return $rank;
  }
  
  
}