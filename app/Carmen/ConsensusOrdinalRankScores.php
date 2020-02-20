<?php

namespace App\Carmen;

use App\RawScore;
use App\Carmen\ScoringMethod;

class ConsensusOrdinalRankScores extends ScoringMethod {

  protected $choirs_remaining = [];
  protected $max_level = null;
  protected $level_pointer = 1;
  protected $recursions = 0;


  public function __construct($weightedScores, $penalties = false)
  {
    parent::__construct($weightedScores, $penalties);
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

    $rank_by_judge = $this->totals($caption_id, $scoreField);
    //if($rank_by_judge->count()) dd($rank_by_judge);

    // Sort
    $rank = $this->sort_and_assign_rank($rank_by_judge);

    $this->total_ranked[$key] = $rank;
    return $rank;
  }


  public function sort_and_assign_rank($rank_by_judge)
  {
    $sorted = collect();

    // Make a list of choirs that need to be compared.  Only include choirs
    // that have at least some scores/ranking from at least one judge.
    $this->choirs_remaining = [];
    $rank_by_judge->each(function($judge_rankings, $key){
      $judge_rankings->each(function($ranking, $key){
        $this->choirs_remaining[] = $ranking['choir_id'];
      });
    });
    $this->choirs_remaining = array_unique($this->choirs_remaining);

    $rank_by_judge = $rank_by_judge->toArray();
    $choirs_in_rank_order = [];
    $rank_to_assign = 1;
    $rank_ceiling = 1;

    $this->max_level = count($this->choirs_remaining);
    $this->level_pointer = 1;

    while(count($this->choirs_remaining)){
      $top_choir = $this->get_top_choir($rank_by_judge);
      if(!$this->recursions){
        $this->level_pointer++;
      }

      foreach($top_choir as $choir_id){
        $choir = [];
        $choir['choir_id'] = $choir_id;
        $choir['rank'] = $rank_to_assign;
        $choir['tied'] = count($top_choir) > 1 ? 1 : 0;
        $choirs_in_rank_order[$choir_id] = $choir;
      }

      $rank_to_assign = $rank_to_assign + count($top_choir);

    }

    foreach($choirs_in_rank_order as $choir_id => $choir){
      $sorted->put($choir_id, $choir);
    }

    return $sorted;
  }



  public function get_top_choir(&$rank_by_judge, $level_bump = 0, $choirs = [], $tie_breaker = false){
    $level = $this->level_pointer + $level_bump;

    if($tie_breaker == false){
      $this->recursions = 0;
    }

    //echo '<h3>========================================</h3>';
    //echo '<pre>';
    //echo 'Recursions: '.$this->recursions."\n\n";

    //if(in_array($level, $this->levels_to_skip)){
      //$level = end($this->levels_to_skip) + 1;
    //}

    $choirs = empty($choirs) ? $this->choirs_remaining : $choirs;

    $choir_tally = array_combine($choirs, array_fill(0, count($choirs), 0));

    //echo 'Choirs: ';
    //print_r($choirs);
    //echo "\n\n";

    //echo "Base Level: $this->level_pointer\n\n";
    //echo "Level: $level\n\n";
    //echo "Tie Breaker: ".intval($tie_breaker)."\n\n";

    // Give a tally mark to each choir for every time a judge ranked it at $level or better.
    foreach($rank_by_judge as $judge_id => $rankings){
      foreach($rankings as $choir_id => $choir){
        if(isset($choir_tally[$choir_id]) && $choir['rank'] <= $level){
          $choir_tally[$choir_id]++;
        }
      }
    }

    // Sort by tally marks.
    arsort($choir_tally);

    //echo "Choir Tallies: ";
    //print_r($choir_tally);
    //echo "\n\n";

    // The number of tally marks for the top spot.
    $top_tally = array_values($choir_tally)[0];

    //if($top_tally === 0){
      //$this->levels_to_skip[] = $level;
    //}

    //echo "Top Tally: $top_tally\n\n";

    // Get the choirs that have the top number of tally marks.  (Could be more than one.)
    $top_choir = array_filter($choir_tally, function($tally, $choir_id) use ($top_tally){
      return $tally == $top_tally;
    }, ARRAY_FILTER_USE_BOTH);

    // We just need the choir IDs, which are the array keys.
    $top_choir = array_keys($top_choir);

    //echo "Top Choir: ";
    //print_r($top_choir);
    //echo "\n\n";

    // Try to only return one top choir. If there is a tie at this level, recurse and
    // examine the next level until we find a unique winner or else we run out of levels
    // to evaluate.  If we run out of levels, then it is a true tie.  All tied choir IDs
    // will be returned.
    if(count($top_choir) > 1 && $level < $this->max_level){

      // Make a copy of $rank_by_judge and modify it.
      $rbj_tied = $rank_by_judge;
      foreach($rbj_tied as &$rankings){
        // Filter the rankings to only contain data about the tied choirs.
        $rankings = array_filter($rankings, function($choir, $choir_id) use ($top_choir){
          return in_array($choir_id, $top_choir);
        }, ARRAY_FILTER_USE_BOTH);
      }

      // Recurse to break the tie.
      $this->recursions++;
      $top_choir = $this->get_top_choir($rbj_tied, $this->recursions, $top_choir, true);
    }

    // Remove the winning choir from the rankings that still need to be considered,
    // but don't do this during a recursive tie-breaker run.
    if(!$tie_breaker){
      foreach($rank_by_judge as $judge_id => &$rankings){
        foreach($rankings as $choir_id => $choir){
          if(in_array($choir_id, $top_choir)){
            $this->choirs_remaining = array_diff($this->choirs_remaining, [$choir_id]);
            unset($rankings[$choir_id]);
            break;
          }
        }
      }
    }

    //echo '</pre>';



    return $top_choir;
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


}
