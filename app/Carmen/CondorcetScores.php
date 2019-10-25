<?php

namespace App\Carmen;

use App\RawScore;
use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\Vote;

class CondorcetScores {
  protected $elections = [];
  
  protected $weightedScores;
  protected $penalties;
  protected $advanced_method = false;
  
  protected $judges = [];
  protected $choirs = [];
  protected $captions = [];
  
  protected $judges_per_election = [];
  
  protected $calculated_scores = [];
  protected $ranked = [];
  protected $total_ranked = [];
  protected $totaled = [];
  
  public $score_by_judge_and_caption = [];
  protected $weightedScore_by_judge_and_caption = [];
  
  protected $score_vote_rank_by_judge_and_caption = [];
  protected $weightedScore_vote_rank_by_judge_and_caption = [];
  
  protected $score_by_judge_overall = [];
  protected $weightedScore_by_judge_overall = [];
  
  protected $score_vote_rank_by_judge_overall = [];
  protected $weightedScore_vote_rank_by_judge_overall = [];
  
  
  public function __construct($weightedScores, $penalties = false)
  {
    $this->weightedScores = $weightedScores;
    $this->penalties = $penalties;

    $this->judges = $this->weightedScores->unique('judge_id')->pluck('judge_id');
    $this->choirs = $this->weightedScores->unique('choir_id')->pluck('choir_id');
    $this->captions = $this->weightedScores->unique('criterion_caption_id')->pluck('criterion_caption_id');
  }
  
  
  public function total_raw_rank($caption_id = false)
  {
    return $this->calculate_rank($caption_id, 'score');
  }
  
  
  public function total_weighted_rank($caption_id = false)
  {
    return $this->calculate_rank($caption_id, 'weightedScore');
  }
  
  
  public function calculate_rank($caption_id = false, $score_field = 'score')
  {
    $results = collect();
    
    if($caption_id){
      
      foreach($this->judges as $judge_id){
        $this->vote_rank_by_judge_and_caption($judge_id, $caption_id, $score_field);
      }
      
      $election_key = ($score_field === 'weightedScore') ? 'caption_'.$caption_id.'_weighted' : "caption_$caption_id";
      
    } else {
      
      foreach($this->judges as $judge_id){
        $this->vote_rank_by_judge_overall($judge_id, $score_field);
      }
      
      $election_key = ($score_field === 'weightedScore') ? 'overall_weighted' : 'overall';
      
    }
    
    $election_results = $this->elections[$election_key]->getResult($this->advanced_method);
    
    foreach($election_results as $rank => $candidates){
      foreach($candidates as $candidate){
        $choir_id = intval($candidate->getName());
        $tied = count($candidates) > 1 ? 1 : 0;
        $results->put($choir_id,['choir_id' => $choir_id, 'rank' => $rank, 'tied' => $tied]);
      }
    }
    
    return $results;
    
  }
  
  
  public function vote_rank_by_judge_overall($judge_id, $score_field)
  {
    $store_property = $score_field.'_vote_rank_by_judge_overall';
    
    if(array_key_exists($judge_id, $this->{$store_property})){
      return $this->{$store_property}[$judge_id];
    }

    $scores = $this->scores_by_judge_overall($judge_id, $score_field);

    if($scores->count()){
      
      // Sort by sum descending
      $sorted = $scores->sortByDesc('score');

      $this->{$store_property}[$judge_id] = $this->assign_rank($sorted);

      $vote_array = [];

      foreach($this->{$store_property}[$judge_id] as $choir_id => $vote_rank){
        $rank = $vote_rank['rank'];
        if(!array_key_exists($rank, $vote_array)){
          $vote_array[$rank] = [];
        }
        $vote_array[$rank][] = "$choir_id";
      }

      $vote = new Vote($vote_array);

      if($score_field === 'weightedScore'){
        $election_key = 'overall_weighted';
      } else {
        $election_key = 'overall';
      }

      if(empty($this->elections[$election_key])){
        $this->make_election($election_key);
      }
      $this->elections[$election_key]->addVote($vote, "$judge_id");
      $this->judges_per_election[$election_key]++;

      return $this->{$store_property}[$judge_id];
      
    } else {
      return collect();
    }
  }
  
  
  protected function scores_by_judge_overall($judge_id, $score_field)
  {
    $store_property = $score_field.'_by_judge_overall';
    
    if(array_key_exists($judge_id, $this->{$store_property})){
      return $this->{$store_property}[$judge_id];
    }

    // Create new $scores collection
    $scores = collect();

    // Loop through choirs and compare sums
    $this->choirs->each(function($choir_id, $key) use ($judge_id, $score_field, $scores){
      // Get the sum of the weighted scores for each choir
      $query = $this->weightedScores
        ->where('choir_id', $choir_id)
        ->where('judge_id', $judge_id);

      // Get the sum
      $score = $query->sum($score_field);

      // Subtract any penalties from the score
      if($score && $this->penalties){
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
      
      // Add the choir and score to the $scores collection
      if($score){
        $scores->put($choir_id, ['choir_id' => $choir_id, 'score' => $score]);
      }
    });

    $this->{$store_property}[$judge_id] = $scores;

    return $scores;
  }
  
  
  public function vote_rank_by_judge_and_caption($judge_id, $caption_id, $score_field)
  {
    $store_property = $score_field.'_vote_rank_by_judge_and_caption';
    
    if(array_key_exists($judge_id."x".$caption_id, $this->{$store_property})){
      return $this->{$store_property}[$judge_id."x".$caption_id];
    }

    $scores = $this->scores_by_judge_and_caption($judge_id, $caption_id, $score_field);
    
    if($scores->count()){

      // Sort by sum descending
      $sorted = $scores->sortByDesc('score');

      $this->{$store_property}[$judge_id.'x'.$caption_id] = $this->assign_rank($sorted);

      $vote_array = [];

      foreach($this->{$store_property}[$judge_id.'x'.$caption_id] as $vote_rank){
        $choir_id = $vote_rank['choir_id'];
        $rank = $vote_rank['rank'];
        if(!array_key_exists($rank, $vote_array)){
          $vote_array[$rank] = [];
        }
        $vote_array[$rank][] = "$choir_id";
      }
      $vote = new Vote($vote_array);


      if($score_field === 'weightedScore'){
        $election_key = 'caption_'.$caption_id.'_weighted';
      } else {
        $election_key = 'caption_'.$caption_id;
      }

      if(empty($this->elections[$election_key])){
        $this->make_election($election_key);
      }
      $this->elections[$election_key]->addVote($vote, "$judge_id");
      $this->judges_per_election[$election_key]++;

      return $this->{$store_property}[$judge_id.'x'.$caption_id];
      
    } else {
      return collect();
    }
  }
  
  
  protected function scores_by_judge_and_caption($judge_id, $caption_id, $score_field)
  {
    $store_property = $score_field.'_by_judge_and_caption';
    
    if(array_key_exists($judge_id."x".$caption_id, $this->{$store_property})){
      return $this->{$store_property}[$judge_id."x".$caption_id];
    }

    // Create new $scores collection
    $scores = collect();

    // Loop through choirs and compare sums
    $this->choirs->each(function($choir_id, $key) use ($judge_id, $caption_id, $score_field, $scores){
      // Get the sum of the weighted scores for each choir
      $query = $this->weightedScores
        ->where('choir_id', $choir_id)
        ->where('judge_id', $judge_id)
        ->where('criterion_caption_id', $caption_id);
      
      // Get the sum
      $score = $query->sum($score_field);

      // Add the choir and score to the $scores collection
      if($score){
        $scores->put($choir_id, ['choir_id' => $choir_id, 'score' => $score]);
      }
    });

    $this->{$store_property}[$judge_id."x".$caption_id] = $scores;

    return $scores;
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
    $rank = $this->assign_rank($sorted);
    $this->total_ranked[$key] = $rank;
    
    return $rank;
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
    if($judge_id && $caption_id){
      $rank = $this->vote_rank_by_judge_and_caption($judge_id, $caption_id, 'weightedScore');
    } elseif($judge_id && !$caption_id){
      $rank = $this->vote_rank_by_judge_overall($judge_id, 'weightedScore');
    } else {
      $rank = $this->total_weighted_rank($caption_id);
    }
    
    return $rank;
  }
  
  
  protected function assign_rank($sortedTotals)
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

    return $rank;
  }
  
  
  protected function make_election($name)
  {
    $this->elections[$name] = new Election();
    
    foreach($this->choirs as $choir_id){
      $this->elections[$name]->addCandidate("$choir_id");
    }
    
    $this->judges_per_election[$name] = 0;
  }
  
  
  public function pairwise($election_key = 'overall_weighted')
  {
    if(isset($this->elections[$election_key])){
      return $this->elections[$election_key]->getPairwise()->getExplicitPairwise();
    }
    
    return null;
  }
  
  
  public function pairwise_bit($election_key, $choir_id, $choir_comp_id)
  {
    
    if($choir_id === $choir_comp_id){
      return 0;
    }
    
    $pairwise = $this->pairwise($election_key);
    
    if($pairwise && $choir_id && $choir_comp_id){
      
      $judge_count = $this->judges_per_election[$election_key];
      $half_count = $judge_count / 2;
      
      $value = $pairwise[$choir_id]['win'][$choir_comp_id];
      return $value >= $half_count ? 1 : 0;
      
    }
    
    return null;
  }
  
  
  public function pairwise_bit_sum($election_key, $choir_id)
  {
    $pairwise = $this->pairwise($election_key);
    $sum = 0;
    
    if($pairwise && $choir_id){
      
      $judge_count = $this->judges_per_election[$election_key];
      $half_count = $judge_count / 2;
      
      foreach($pairwise[$choir_id]['win'] as $choir_comp_id => $value){
        $bit = $value >= $half_count ? 1 : 0;
        $sum += $bit;
      }
      
      return $sum;
      
    }
    
    return null;
  }
  
  
}