<?php

namespace App\Carmen;

use App\RawScore;
use App\Division;
use App\Round;

class CountExpectedScores {

  protected $round;
  protected $captions;
  protected $choirCount;
  protected $expectedTotalCount;

  public function __construct(Round $round)
  {
    $round->load(['choirs', 'division', 'division.judges', 'division.sheet', 'division.sheet.criteria']);
    $this->round = $round;
  }


  public function run()
  {
    $this->getDistinctCaptions();
    $this->countChoirs();
    $this->countCaptionJudges();
    $this->countCaptionCriteria();
    $this->calculateExpectedTotalCaptionCount();
    $this->calculateExpectedTotalCount();

    return $this->expectedTotalCount;
  }

  public function getDistinctCaptions()
  {
    $this->captions = $this->round->division->sheet->criteria->unique('caption_id')->pluck('caption_id', 'caption_id')->toArray();

    foreach ($this->captions as $key => $caption) {
      $this->captions[$key] = [
        'judgeCount' => 0,
        'criteriaCount' => 0,
        'expectedTotalCount' => 0
      ];
    }
  }

  public function countChoirs()
  {
    $this->choirCount = $this->round->choirs->count();
  }

  public function countCaptionJudges()
  {
    foreach ($this->round->division->judges as $judge) {
      if (!array_key_exists($judge->pivot->caption_id, $this->captions)) continue;

      $this->captions[$judge->pivot->caption_id]['judgeCount']++;
    }
  }

  public function countCaptionCriteria()
  {
    foreach ($this->captions as $key => $caption) {
      $this->captions[$key]['criteriaCount'] = $this->round->division->sheet->criteria->where('caption_id', $key)->count();
    }
  }

  public function calculateExpectedTotalCaptionCount()
  {
    foreach ($this->captions as $key => $caption) {
      $this->captions[$key]['expectedTotalCount'] = $this->choirCount * $caption['judgeCount'] * $caption['criteriaCount'];
    }
  }

  public function calculateExpectedTotalCount()
  {
    $this->expectedTotalCount = 0;

    foreach ($this->captions as $key => $caption) {
      $this->expectedTotalCount = $this->expectedTotalCount + $this->captions[$key]['expectedTotalCount'];
    }

    return $this->expectedTotalCount;
  }
}
