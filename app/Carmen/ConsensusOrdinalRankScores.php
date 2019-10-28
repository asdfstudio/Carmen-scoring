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
    $key = $caption_id ? $caption_id.'x'.$scoreField : '0x'.$scoreField;
    if(array_key_exists($key, $this->total_ranked)){
      return $this->total_ranked[$key];
    }
    
    $rank_by_judge = $this->totals($caption_id);
    
    // Sort
    $rank = $this->sort_and_assign_rank($rank_by_judge);
    
    $this->total_ranked[$key] = $rank;
    return $rank;
  }
  
  
  public function sort_and_assign_rank($rank_by_judge)
  {
    $sorted = collect();
    $rank_by_judge = $rank_by_judge->toArray();
    $choirs_in_rank_order = [];
    $rank_to_assign = 1;
    
    while(count($choirs_in_rank_order) < count($this->choirs)){
      
      $top_choir = $this->get_top_choir($rank_by_judge);
      
      foreach($top_choir as $choir_id){
        $choir = [];
        $choir['choir_id'] = $choir_id;
        $choir['rank'] = $rank_to_assign;
        $choir['tied'] = count($top_choir) > 1 ? 1 : 0;
        $choirs_in_rank_order[$choir_id] = $choir;
      }
      
      //if(count($rank_by_judge) == 2 && count($choirs_in_rank_order) > 9)
        //dd($choirs_in_rank_order);
      
      $rank_to_assign++;
      
    }
    
    foreach($choirs_in_rank_order as $choir_id => $choir){
      $sorted->put($choir_id, $choir);
    }
    
    return $sorted;
  }
  
  
  public function get_top_choir(&$rank_by_judge, $level = 1, $choirs = [], $tie_breaker = false){
    
    $choirs = empty($choirs) ? $this->choirs->toArray() : $choirs;
    $choir_tally = array_combine($choirs, array_fill(0, count($choirs), 0));
    
    // Give a tally mark to each choir for every time a judge ranked it at $level or better.
    foreach($rank_by_judge as $judge_id => $rankings){
      foreach($rankings as $choir_id => $choir){
        if($choir['rank'] <= $level){
          $choir_tally[$choir_id]++;
        }
      }
    }
    
    // Sort by tally marks.
    arsort($choir_tally);
    
    // The number of tally marks for the top spot.
    $top_tally = array_values($choir_tally)[0];
    
    // Get the choirs that have the top number of tally marks.  (Could be more than one.)
    $top_choir = array_filter($choir_tally, function($tally, $choir_id) use ($top_tally){
      return $tally == $top_tally;
    }, ARRAY_FILTER_USE_BOTH);
    
    // We just need the choir IDs, which are the array keys.
    $top_choir = array_keys($top_choir);
    
    // Try to only return one top choir. If there is a tie at this level, recurse and
    // examine the next level until we find a unique winner or else we run out of levels
    // to evaluate.  If we run out of levels, then it is a true tie.  All tied choir IDs
    // will be returned.
    if(count($top_choir) > 1){
      
      // Make a copy of $rank_by_judge and modify it.
      $rbj_tied = $rank_by_judge;
      foreach($rbj_tied as &$rankings){
        // Filter the rankings to only contain data about the tied choirs.
        $rankings = array_filter($rankings, function($choir, $choir_id) use ($top_choir){
          return in_array($choir_id, $top_choir);
        }, ARRAY_FILTER_USE_BOTH);
      }
      
      // If we have any data left to examine, recurse.
      if(count(current($rbj_tied))){
        $top_choir = $this->get_top_choir($rbj_tied, $level+1, $top_choir, true);
      }
    }
    
    // Remove the winning choir from the rankings that still need to be considered,
    // but don't do this during a recursive tie-breaker run.
    if(!$tie_breaker){
      foreach($rank_by_judge as $judge_id => &$rankings){
        foreach($rankings as $choir_id => $choir){
          if(in_array($choir_id, $top_choir)){
            unset($rankings[$choir_id]);
          }
        }
      }
    }
    
    return $top_choir;
  }
  
  
  public function total($choir_id, $caption_id = false)
  {
    // This function is not applicable to Consensus Ordinal Rank, but it is
    // retained for compatibility with the shared view output.
    return null;
  }
  
  
  public function totals($caption_id = false, $scoreField = 'weightedScore')
  {
    $key = $caption_id ? $caption_id.'x'.$scoreField : '0x'.$scoreField;
    if(array_key_exists($key, $this->totals)){
      return $this->totals[$key];
    }
    
    $totals = collect();
    
    // Build an array that shows how many times the choir has been given each rank.
    $this->judges->each(function($judge_id, $key) use ($caption_id, &$totals, $scoreField) {
      $rank = $this->rank($judge_id, $caption_id, $scoreField);
      if($rank->count()){
        $totals->put($judge_id, $rank);
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