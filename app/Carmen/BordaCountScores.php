<?php

namespace App\Carmen;

use App\RawScore;
use App\Carmen\CondorcetScores;
use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\Vote;

class BordaCountScores extends CondorcetScores {
  
  protected $advanced_method = 'Borda Count';
  
  public function __construct($weightedScores, $penalties = false)
  {
    parent::__construct($weightedScores, $penalties);
  }
  
  
}