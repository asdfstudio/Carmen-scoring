<?php

namespace App\Carmen;

use App\RawScore;

class WeightedScores {

    protected $rawScores;
    protected $weightedScores;
    protected $captionWeightingId;
    protected $musicWeighting = 1;
    protected $showWeighting = 1;

    protected $choirId;
    protected $judgeId;
    protected $captionId;

    public function __construct($rawScores, $captionWeightingId = false)
    {
      $this->rawScores = $rawScores;
      $this->captionWeightingId = $captionWeightingId;

      if($this->captionWeightingId == 1){
        $this->musicWeighting = 1.5;
      }

      $this->rawScores->map(function ($item, $key){
        // The caption ID comes through in different ways for different contexts.
        $caption_id = isset($item->criterion_caption_id) ? $item->criterion_caption_id : $item->criterion->caption_id;
        
        if($caption_id == 1){
          $item->weightedScore = $item->score * $this->musicWeighting;
        } elseif($caption_id == 2){
          $item->weightedScore = $item->score * $this->showWeighting;
        } else {
          $item->weightedScore = $item->score;
        }
        
        return $item;
      });
      
      $this->weightedScores = $this->rawScores;
    }

    public function all($keys = null)
    {
      return $this->weightedScores;
    }

}
