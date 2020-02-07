<?php

namespace App\Carmen;

use App\RawScore;
use App\Carmen\ScoringMethod;
use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\Vote;

class CondorcetScores extends ScoringMethod {
  
  protected $elections = [];
  protected $advanced_method = false;
  protected $judges_per_election = [];
  protected $score_by_judge_and_caption = [];
  protected $weightedScore_by_judge_and_caption = [];
  protected $score_vote_rank_by_judge_and_caption = [];
  protected $weightedScore_vote_rank_by_judge_and_caption = [];
  protected $score_by_judge_overall = [];
  protected $weightedScore_by_judge_overall = [];
  protected $score_vote_rank_by_judge_overall = [];
  protected $weightedScore_vote_rank_by_judge_overall = [];
  
  
  public function __construct($weightedScores, $penalties = false)
  {
    parent::__construct($weightedScores, $penalties);
  }
  
  
  public function calculate_rank($score_field = 'score', $caption_id = false)
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
    
    if(isset($this->elections[$election_key])){
      $election_results = $this->elections[$election_key]->getResult($this->advanced_method);

      $carmen_rank = 0;
      $previous_condorcet_rank = null;
      $loop = 0;

      foreach($election_results as $condorcet_rank => $candidates){
        foreach($candidates as $candidate){
          $loop++;

          if($condorcet_rank !== $previous_condorcet_rank){
            $carmen_rank = $loop;
            $previous_condorcet_rank = $condorcet_rank;
          }

          $choir_id = intval($candidate->getName());
          $tied = count($candidates) > 1 ? 1 : 0;
          $results->put($choir_id,['choir_id' => $choir_id, 'rank' => $carmen_rank, 'tied' => $tied]);

        }
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
          //$overall_penalty_amount = $choir_penalties->where('apply_per_judge', 0)->sum('amount');
          //$score = $score - $overall_penalty_amount;

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
  
  
  public function rank($judge_id = false, $caption_id = false)
  {
    $key = $judge_id."x".$caption_id;
    if(array_key_exists($key, $this->ranked)){
      return $this->ranked[$key];
    }

    if($judge_id && $caption_id){
      $rank = $this->vote_rank_by_judge_and_caption($judge_id, $caption_id, 'weightedScore');
    } elseif($judge_id && !$caption_id){
      $rank = $this->vote_rank_by_judge_overall($judge_id, 'weightedScore');
    } else {
      $rank = $this->total_weighted_rank($caption_id);
    }
    
    return $this->ranked[$key] = $rank;
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
      
      if(isset($pairwise[$choir_id]) && isset($pairwise[$choir_id]['win']) && isset($pairwise[$choir_id]['win'][$choir_comp_id])){
        $value = $pairwise[$choir_id]['win'][$choir_comp_id];
        return $value >= $half_count ? 1 : 0;
      } else {
        return '';
      }
      
    }
    
    return null;
  }
  
  
  public function pairwise_bit_sum($election_key, $choir_id)
  {
    $pairwise = $this->pairwise($election_key);
    $sum = 0;
    
    if($pairwise && $choir_id && isset($pairwise[$choir_id]) && isset($pairwise[$choir_id]['win'])){
      
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