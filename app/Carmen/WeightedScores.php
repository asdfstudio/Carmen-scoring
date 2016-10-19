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

      return $this->weightedScores;
    }


    public function all()
    {
      return $this->weightedScores;
    }

    protected function assign_weighting()
    {
      $this->weightedScores = $this->rawScores->map(function ($item, $key)
      {
        //dd($item->criterion->caption_id);
        //echo $item->get('criterion.caption_id');

        if($item->criterion->caption_id == 1)
        {
          $weightedScore = $item->score * $this->musicWeighting;
        }
        elseif($item->criterion->caption_id == 2)
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

    public function choir($id)
    {
      $this->choirId = $id;
      $this->rawScores = $this->rawScores->where('choir_id',$id);
      return $this;
    }

    public function judge($id)
    {
      $this->judgeId = $id;
      $this->rawScores = $this->rawScores->where('judge_id',$id);
      return $this;
    }

    public function caption($id)
    {
      $this->captionId = $id;
      $this->rawScores = $this->rawScores->where('criterion.caption_id',$id);
      return $this;
    }

    public function sum($reset = false)
    {
      $sum = $this->rawScores->sum('score');

      if($this->captionId == 1)
      {
        $sum = $sum * $this->musicWeighting;
      }

      if($this->captionId == 2)
      {
        $sum = $sum * $this->showWeighting;
      }

      // reset
      if($reset)
        $this->rawScores = $this->rawScoresOriginal;

      return $sum;
    }

}
