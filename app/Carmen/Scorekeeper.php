<?php

namespace App\Carmen;

use App\RawScore;
use Loggy;

class Scorekeeper {

	protected $judge_id;
	protected $division_id;
	protected $round_id;
	protected $choir_id;
	protected $criterion_id;
	protected $score;


	public function __construct($parameters = array())
	{
		foreach($parameters as $key => $value)
		{
      $this->$key = $value;
    }
	}


	public function choir($choir_id)
	{
		$this->choir_id = $choir_id;

		return $this;
	}


	public function criterion($criterion_id)
	{
		$this->criterion_id = $criterion_id;

		return $this;
	}


	public function score($score)
	{
		// Set score equal to 0 if provided score is outside
		// of 0-10 range
		if($score < 0 OR $score > 10) $score = 0;

		$this->score = $score;

		return $this;
	}


	public function save_multiple_scores($scores = array())
	{
		$recordsSaved = 0;

		foreach($scores as $criterion_id => $score)
		{
			$saved = $this->criterion($criterion_id)->score($score)->save();

			if($saved)
			{
				$recordsSaved++;
			}
		}

		return $recordsSaved;
	}


	public function save_multiple_choirs_scores($choirScores = array())
	{
		$recordsSaved = 0;

		foreach($choirScores as $choir_id => $scores)
		{
			$saved = $this->choir($choir_id)->save_multiple_scores($scores);

			if($saved)
			{
				$recordsSaved = $recordsSaved + $saved;
			}
		}

		return $recordsSaved;
	}


	public function save()
	{
		$data = [
			'division_id' => $this->division_id,
			'round_id' => $this->round_id,
			'choir_id' => $this->choir_id,
			'judge_id' => $this->judge_id,
			'criterion_id' => $this->criterion_id
		];

		$score = RawScore::firstOrNew($data);

		if ($score->score != $this->score) {
			$scoreChanged = true;
		} else {
			$scoreChanged = false;
		}

		$score->score = (float) $this->score;
		$result = $score->save();

		if ($scoreChanged) {
			Loggy::write('scores', $score);
		}

		return $result;
	}

}
