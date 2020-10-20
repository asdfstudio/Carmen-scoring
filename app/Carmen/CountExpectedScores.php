<?php

namespace App\Carmen;

use App\RawScore;
use App\Division;

class CountExpectedScores {

  protected $division;
  protected $captions;
  protected $choirCount;
  protected $expectedTotalCount;

  public function __construct(Division $division)
  {
    $division->load(['choirs', 'round', 'round.judges', 'round.sheet', 'round.sheet.criteria']);
    $this->division = $division;
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
    $this->captions = $this->division->round->sheet->criteria->unique('caption_id')->pluck('caption_id', 'caption_id')->toArray();

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
    $this->choirCount = $this->division->choirs->count();
  }

  public function countCaptionJudges()
  {
    foreach ($this->division->round->judges as $judge) {
      if (!array_key_exists($judge->pivot->caption_id, $this->captions)) continue;

      $this->captions[$judge->pivot->caption_id]['judgeCount']++;
    }
  }

  public function countCaptionCriteria()
  {
    foreach ($this->captions as $key => $caption) {
      $this->captions[$key]['criteriaCount'] = $this->division->round->sheet->criteria->where('caption_id', $key)->count();
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
