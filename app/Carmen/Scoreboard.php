<?php

namespace App\Carmen;

use App\RawScore;
use App\Division;
use App\Round;
use App\Penalty;
use App\Carmen\WeightedScores;
use App\Carmen\RankedScores;

class Scoreboard {

	protected $division_id;
	protected $round_id;
	protected $division;
	protected $round;
	protected $rounds;
	public $penalties;
	//protected $judge_id;

	//protected $criteria;
	//protected $judges;

	public $rawScores;
	public $weightedScores;
	public $rankedScores;
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
	}

	protected function getRawScores()
	{
		//$query = RawScore::with('judge','choir','criterion');
		$query = RawScore::with('criterion');

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

		return $this->rawScores = $query->get();
	}


	protected function getWeightedScores()
	{
		$this->getDivision();

		$weightedScoresClass = new WeightedScores($this->rawScores,        $this->division->caption_weighting_id);

		$this->weightedScores = $weightedScoresClass->all();
		$this->extendedRawScores = $this->weightedScores;
		return $this->weightedScores;
	}

	protected function getPenalties()
	{
		$penalties_raw = Round::find($this->round_id)->penalties;

		$penalties = collect();

		$penalties_raw->each(function($item, $key) use ($penalties){
      $penalties->put($key, [
				'choir_id' => $item->pivot->choir_id,
				'amount' => $item->amount,
				'apply_per_judge' => $item->apply_per_judge
			]);
    });

		return $this->penalties = $penalties;
	}

	protected function getRound()
	{
		if(is_array($this->round_id))
		{
			$this->round_id = array_shift($this->round_id);
		}
		return $this->round = Round::find($this->round_id);
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


}
