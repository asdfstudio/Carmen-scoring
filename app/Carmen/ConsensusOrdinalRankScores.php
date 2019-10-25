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
  
  
  public function sort_and_assign_rank($rankings)
  {
    $sorted = collect();
    $rank_to_assign = 1;
    $rank_to_sort = count($rankings);

    // Sort rankings.
    while($rank_to_sort){
      $rankings = $rankings->sortByDesc($rank_to_sort);
      $rank_to_sort--;
    }
    
    // Convert to array with zero-based index.
    $rankings = array_values($rankings->toArray());
    
    // Loop through all the ranking data (grouped by choir).
    for($i = 0; $i < count($rankings); $i++){
      
      $current = $rankings[$i];
      $next = !empty($rankings[$i+1]) ? $rankings[$i+1] : null;
      
      for($j = 1; $j <= count($rankings); $j++){
        
        $current['rank'] = $rank_to_assign;
        
        if(!$next || $current[$j] > $next[$j]){
          $current['tied'] = 0;
          $rank_to_assign++;
          break;
        }
        
        if($next && $current[$j] == $next[$j]){
          $current['tied'] = 1;
          $next['tied'] = 1;
        }
        
      }
      
      $rankings[$i] = $current;
      if(!empty($next)){
        $rankings[$i+1] = $next;
      }
      
    }
    
    foreach($rankings as $choir){
      $sorted->put($choir['choir_id'], $choir);
    }
    
    return $sorted;
    
    //dd($sorted);
      /*
      // Get all the choirs who have the highest value for the level.
      $choirs_in_rank = $rankings->where($i, $highest_value);
      
      //if(count($choirs_in_rank) == 2){
        dd($rankings);
      //}
      
      // Remove these choirs from the rankings list so that they don't get evaluated for lower ranks.
      foreach($choirs_in_rank as $choir_id => $choir){
        $rankings->forget($choir_id);
      }
      
      //echo print_r($choirs_in_rank, true)."\n\n";
      
      // If more than one choir shares the highest value for this rank, we evaluate subsequent
      // ranks recursively to see if we can break the tie.
      if(count($choirs_in_rank) > 1 && $i < $rank_last){
        // This is not the last rank, so we can recursively evaluate the next rank to break the tie.
        $choirs_in_rank = $this->sort_and_assign_rank($choirs_in_rank, $i+1);
      } elseif(count($choirs_in_rank) > 1 && $i == $rank_last){
        // This is the last rank and we still have a tie.
        $choirs_in_rank->each(function($item, $key){
          $item['tied'] = 1;
        });
      }
      
      $rank_to_assign = $i;
      $previous_value = null;
      
      foreach($choirs_in_rank as $choir_id => $choir){
        // If this is not the first loop (null previous value) and not a tie,
        // then increment the $rank_to_assign.
        if(!is_null($previous_value) && (empty($choir['tied']) || $choir[$i] !== $previous_value)){
          $rank_to_assign++;
        }
        
        $choir['rank'] = $rank_to_assign;
        $previous_value = $choir[$i];
        
        if(empty($choir['tied'])){
          $choir['tied'] = 0;
        }
        
        $choirs_in_rank->put($choir_id, $choir);
      }
      
      // If we handled more than one choir in this loop, we need to fastforward the incrementer.
      $i = $rank_to_assign;
      
      // Sort by the rank we just assinged.
      $choirs_in_rank = $choirs_in_rank->sortBy('rank');
      
      foreach($choirs_in_rank as $choir_id => $choir){
        $sorted->put($choir_id, $choir);
      }
      
    }
    //echo '</pre>';
    
    return $sorted;
    */
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