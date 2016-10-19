<?php

namespace App\Carmen;

use App\RawScore;

class Scoreboard {
	
	protected $judge_id;
	protected $division_id;
	protected $round_id;
	
	protected $criteria;
	protected $judges;
	
	protected $rawScores;
	protected $rankedScores;
	protected $judgeScores;
	protected $criteriaScores;
	
	
	public function __construct($parameters = array())
	{
		foreach($parameters as $key => $value) 
		{
      $this->$key = $value;
    }
		
		$this->retrieve();
		
		// Distinct criteria
		$this->criteria = $this->rawScores->unique('criterion_id')->pluck('criterion_id');
		
		// Distinct judges
		$this->judges = $this->rawScores->unique('judge_id')->pluck('judge_id');
		
		$this->calculate_rank();
	}
	
	
	// Retrieve raw scores from database
	public function retrieve()
	{
		$params = [
			'division_id' => $this->division_id,
			'round_id' => $this->round_id
		];
		
		if($this->judge_id)
			$params['judge_id'] = $this->judge_id;
		
		array_filter($params);
		
		return $this->rawScores = RawScore::where($params)->get();
	}
	
	
	// Create ranked scores
	public function ranked_scores()
	{
		$this->rankedScores = $this->rawScores;
		
		//dd($this->judgeScores);
		
		foreach($this->judgeScores as $this->judge_id => $criteriaRanks)
		{
			//echo '<li>'.$this->judge_id.'</li>';
			foreach($criteriaRanks as $criterion_id => $choirPoints)
			{
				//echo '<li>'.$this->judge_id.', '.$criterion_id.'</li>';
				foreach($choirPoints as $choir_id => $rankPoints)
				{
					//echo '<li>'.$this->judge_id.', '.$criterion_id.', '.$choir_id.', '.$rankPoints.'</li>';
					$this->rankedScores->where('judge_id',$this->judge_id)->where('criterion_id',$criterion_id)->where('choir_id',$choir_id)->map(function($score) use ($rankPoints){
						$score['rank'] = $rankPoints;
						return $score;
					});
				}
			}
		}
		
		return $this->rankedScores;
	}
	
	
	/*public function ranked_scores()
	{
		$this->rankedScores = $this->rawScores;
		
		dd($this->judgeScores);
		
		foreach($this->criteriaScores as $criterion_id => $choirRanks)
		{
			foreach($this->judges as $this->judge_id)
			{
				foreach($choirRanks as $choir_id => $rankPoints)
				{
					$this->rankedScores->where('judge_id',$this->judge_id)->where('criterion_id',$criterion_id)->where('choir_id',$choir_id)->map(function($score) use ($rankPoints){
						$score['rank'] = $rankPoints;
						return $score;
					});
				}
			}
		}
		
		return $this->rankedScores;
	}*/
	
	
	public function calculate_rank()
	{		
		foreach($this->criteria as $criterion_id)
		{
			foreach($this->judges as $this->judge_id)
			{
				$this->scores_by_judge_criterion($this->judge_id, $criterion_id);
			}
		}

		return $this->criteriaScores;
	}
	
	
	public function scores_by_judge_criterion($judge_id, $criterion_id)
	{
		$scores = $this->rawScores->where('judge_id',$judge_id)->where('criterion_id',$criterion_id)->sortByDesc('score')->pluck('score','choir_id');
		
		$rankPoints = $this->assign_rank_points($scores);
		
		$this->criteriaScores[$criterion_id] = $rankPoints;
		
		$this->judgeScores[$judge_id][$criterion_id] = $rankPoints;
	}
	
	
	protected function assign_rank_points($scores = array())
	{
		$i = 1;
		$prevScore = false;
		$prevPoints = false;
		
		$rankPoints = array();
		
		foreach($scores as $choir_id => $score)
		{			
			if($score == $prevScore)
				$points = $prevPoints;
			else
				$points = $i;
			
			$rankPoints[$choir_id] = $points;
			
			$prevScore = $score;
			$prevPoints = $points;
			$i++;
		}
		
		return $rankPoints;
	}
	

}