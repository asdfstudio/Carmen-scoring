<?php

namespace App\Carmen;

use DB;
use App\RawScore;
use App\Division;
use App\Round;
use App\Penalty;
use App\ChoirRoundPenalty;
use App\Carmen\WeightedScores;
use App\Carmen\RankedScores;
use App\Carmen\CondorcetScores;
use App\Carmen\ConsensusOrdinalRankScores;

class Scoreboard {

	protected $division_id;
	protected $round_id;
	protected $division;
	protected $round;
	protected $rounds;
	public $penalties;
	protected $judge_id;

	//protected $criteria;
	//protected $judges;

	public $rawScores;
	public $weightedScores;
	public $rankedScores;
  public $bordaCountScores;
	public $condorcetScoresSchulze;
	public $condorcetScoresRankedPairs;
	public $consensusOrdinalRankScores;
  public $rankedScoresForCurrentMethod;
	public $extendedRawScores;
	//protected $judgeScores;
	//protected $criteriaScores;


	public function __construct($parameters = [])
	{
		foreach($parameters as $key => $value)
		{
      $this->$key = $value;
    }

		$this->getRawScores();
		$this->getWeightedScores();
		$this->getPenalties();
		$this->getRankedScores();
    $this->getBordaCountScores();
		$this->getCondorcetScoresSchulze();
		$this->getCondorcetScoresRankedPairs();
		$this->getConsensusOrdinalRankScores();
    $this->getRankedScoresForCurrentMethod();
	}

	protected function getRawScores()
	{
		//$query = RawScore::with('criterion');

		$query = DB::table('raw_scores')
			->join('criteria', 'raw_scores.criterion_id', '=', 'criteria.id')
			->select([
				'raw_scores.id',
				'raw_scores.division_id',
				'raw_scores.round_id',
				'raw_scores.choir_id',
				'raw_scores.judge_id',
				'raw_scores.criterion_id',
				'raw_scores.score',
				'criteria.caption_id as criterion_caption_id'
			])
			->whereNull('raw_scores.deleted_at');

		if($this->division_id)
		{
			$query->where('division_id', $this->division_id);
		}

		if($this->round_id)
		{
			if(is_array($this->round_id))
				$query->whereIn('round_id', $this->round_id);
			else
				$query->where('round_id', $this->round_id);
		}

		// Added 2018-01-04 to speed up scoreboard/reduce memory usage
		if($this->judge_id)
		{
			$query->where('judge_id', $this->judge_id);
		}

		//return $this->rawScores = $query->get();
		return $this->rawScores = collect($query->get());
	}


	protected function getWeightedScores()
	{
		$this->getDivision();

		$weightedScoresClass = new WeightedScores($this->rawScores, $this->division->caption_weighting_id);

		$this->weightedScores = $weightedScoresClass->all();
		$this->extendedRawScores = $this->weightedScores;
		return $this->weightedScores;
	}

	protected function getPenalties()
	{
		//$penalties_raw = Round::find($this->round_id)->penalties;
		//$penalties_raw = Round::whereIn('id', $this->round_id)->get()->penalties;

		$query = ChoirRoundPenalty::with('penalty');

		if($this->round_id)
		{
			if(is_array($this->round_id))
				$query->whereIn('round_id', $this->round_id);
			else
				$query->where('round_id', $this->round_id);
		}

		$penalties_raw = $query->get();

		$penalties = collect();

		$penalties_raw->each(function($item, $key) use ($penalties){

			if ($item->penalty) {
				$penalties->put($key, [
					'choir_id' => $item->choir_id,
					'amount' => $item->penalty->amount,
					'apply_per_judge' => $item->penalty->apply_per_judge
				]);
			}

    });

		return $this->penalties = $penalties;
	}

	protected function getRound()
	{
		if (is_array($this->round_id)) {
			$roundIds = $this->round_id;
			$roundId = array_shift($roundIds);
			//$this->round_id = array_shift($this->round_id);
		} else {
			$roundId = $this->round_id;
		}
		return $this->round = Round::find($roundId);
	}

	protected function getDivision()
	{
		if($this->division_id)
		{
			return $this->division = Division::find($this->division_id);
		}
		else
		{
			$this->getRound();
			return $this->division = $this->round->division;
		}
	}

	protected function getRankedScores()
	{
		return $this->rankedScores = new RankedScores($this->extendedRawScores, $this->penalties);
	}

	protected function getBordaCountScores()
	{
		return $this->bordaCountScores = new BordaCountScores($this->extendedRawScores, $this->penalties);
	}

	protected function getCondorcetScoresSchulze()
	{
		return $this->condorcetScoresSchulze = new CondorcetScoresSchulze($this->extendedRawScores, $this->penalties);
	}

	protected function getCondorcetScoresRankedPairs()
	{
		return $this->condorcetScoresRankedPairs = new CondorcetScoresRankedPairs($this->extendedRawScores, $this->penalties);
	}

	protected function getConsensusOrdinalRankScores()
	{
		return $this->consensusOrdinalRankScores = new ConsensusOrdinalRankScores($this->extendedRawScores, $this->penalties);
	}

  public function getRankedScoresForCurrentMethod()
  {
    switch($this->division->scoring_method_id){
      case 1:
      case 2:
        $this->rankedScoresForCurrentMethod = $this->rankedScores;
        break;
      case 3:
        $this->rankedScoresForCurrentMethod = $this->condorcetScoresRankedPairs;
        break;
      case 4:
        $this->rankedScoresForCurrentMethod = $this->condorcetScoresSchulze;
        break;
      case 5:
        $this->rankedScoresForCurrentMethod = $this->consensusOrdinalRankScores;
        break;
      case 6:
        $this->rankedScoresForCurrentMethod = $this->bordaCountScores;
        break;
      default:
        $this->rankedScoresForCurrentMethod = null;
    }
  }
}
