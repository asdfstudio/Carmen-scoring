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
        $results->put($choir_id,['choir_id' => $choir_id, 'rank' => $rank]);
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

    // Sort by sum descending
    $sorted = $scores->sortByDesc($score_field);

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
      if(empty($this->elections['overall_weighted'])){
        $this->make_election('overall_weighted');
      }
      $this->elections['overall_weighted']->addVote($vote, "$judge_id");
    } else {
      if(empty($this->elections['overall'])){
        $this->make_election('overall');
      }
      $this->elections['overall']->addVote($vote, "$judge_id");
    }
    
    return $this->{$store_property}[$judge_id];
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

    // Sort by sum descending
    $sorted = $scores->sortByDesc($score_field);

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
      if(empty($this->elections['caption_'.$caption_id.'_weighted'])){
        $this->make_election('caption_'.$caption_id.'_weighted');
      }
      $this->elections['caption_'.$caption_id.'_weighted']->addVote($vote, "$judge_id");
    } else {
      if(empty($this->elections["caption_$caption_id"])){
        $this->make_election("caption_$caption_id");
      }
      $this->elections["caption_$caption_id"]->addVote($vote, "$judge_id");
    }
    
    return $this->{$store_property}[$judge_id.'x'.$caption_id];
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
  
  
  protected function assign_rank($sortedTotals)
  {
    // Assign number rank
    $loops = 1;
    $previous_rank = 1;
    $previous_score = false;

    $rank = $sortedTotals->map(function($item, $key) use (&$loops, &$previous_rank,  &$previous_score) {

      if($item['score'] == $previous_score){
        $item['rank'] = $previous_rank;
      } else {
        $item['rank'] = $loops;
        $previous_rank = $loops;
      }

      $previous_score = $item['score'];
      $loops++;

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
  }
  
  
}