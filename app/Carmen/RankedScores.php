<?php

namespace App\Carmen;

use App\RawScore;
use App\Carmen\ScoringMethod;

class RankedScores extends ScoringMethod {
  
  public function __construct($weightedScores, $penalties = false)
  {
    parent::__construct($weightedScores, $penalties);
  }
  
  
  // All RankedScores methods are inherited from the generic ScoringMethod.
  
  
}
