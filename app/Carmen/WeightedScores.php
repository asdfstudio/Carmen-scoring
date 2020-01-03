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

      $this->set_caption_weighting();

      $this->assign_weighting();
    }

    public function all($keys = null)
    {
      return $this->weightedScores;
    }

    protected function assign_weighting()
    {
      $this->weightedScores = $this->rawScores->map(function ($item, $key) {

        if($item->criterion_caption_id == 1)
        {
          $weightedScore = $item->score * $this->musicWeighting;
        }
        elseif($item->criterion_caption_id == 2)
        {
          $weightedScore = $item->score * $this->showWeighting;
        }
        else
        {
          $weightedScore = $item->score;
        }

        $item->weightedScore = $weightedScore;

        return $item;

      });

      return $this->weightedScores;
    }

    protected function set_caption_weighting()
    {
      if($this->captionWeightingId == 1)
      {
        $this->musicWeighting = 1.5;
      }

      return $this->musicWeighting;
    }

}
