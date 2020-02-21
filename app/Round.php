<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Carmen\CountExpectedScores;
use App\RawScore;
use App\Carmen\Scoreboard;
use App\Carmen\Ratings;
use Event;
use App\Events\RoundScoringActivated;
use App\Events\RoundScoringCompleted;

class Round extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['division_id','name', 'sequence', 'max_choirs'];

    protected $ratings;


		public function division()
		{
			return $this->belongsTo('App\Division');
		}

    public function sources()
    {
      return $this->belongsToMany('App\Round', 'round_connections', 'target_round_id', 'source_round_id');
    }

    public function targets()
    {
      return $this->belongsToMany('App\Round', 'round_connections', 'source_round_id', 'target_round_id');
    }

    public function choirs()
    {
      return $this->belongsToMany('App\Choir')->withPivot( 'performance_order')->orderBy('performance_order', 'ASC');
    }

    public function penalties()
    {
      return $this->belongsToMany('App\Penalty', 'choir_penalty')->withPivot('choir_id');
    }

    public function feedback()
		{
			return $this->morphMany('App\Comment', 'subject');
		}


		public function isScoringActive()
		{
			return $this->is_scoring_active ? 'Active' : 'Not Active';
		}


		public function status()
		{
			if($this->is_completed)
			{
				return 'Completed';
			}
			elseif($this->is_scoring_active)
			{
				return 'Active';
			}
			else
			{
				return 'Inactive';
			}
		}

    public function status_slug()
		{
			if($this->is_completed)
			{
				return 'completed';
			}
			elseif($this->is_scoring_active)
			{
				return 'active';
			}
			else
			{
				return 'inactive';
			}
		}


    ///
    public function getStatusAttribute()
    {
      return $this->status();
    }

    public function getStatusSlugAttribute()
    {
      return $this->status_slug();
    }


    public function status_label($class_attr = false)
    {
      $class_array = ['label', 'status', $this->status_slug];

      if($class_attr)
        $class_array[] = $class_attr;

      $class = implode(' ', $class_array);

      return '<span class="'.$class.'">'.$this->status.'</span>';
    }

    ///

    public function getMaxChoirsTextAttribute()
    {
      return $this->max_choirs == 0 ? 'All' : $this->max_choirs;
    }

    public function getFullNameAttribute()
    {
      $h = '';

      if($this->division)
      {
        $h.= $this->division->name . ' - ';
      }

      $h.= $this->name;

      return $h;
    }

    public function activateScoring()
    {
      $this->is_scoring_active = true;
      $this->is_completed = false;
      Event::fire(new RoundScoringActivated($this));
      return $this->save();
    }

    public function deactivateScoring()
    {
      $this->is_scoring_active = false;
      $this->is_completed = false;
      return $this->save();
    }

    public function reactivateScoring()
    {
      $this->is_scoring_active = true;
      $this->is_completed = false;
      return $this->save();
    }

    public function completeScoring()
    {
      $this->is_scoring_active = false;
      $this->is_completed = true;
      Event::fire(new RoundScoringCompleted($this));
      return $this->save();
    }

    public function isMissingScores()
    {
      if (!$this instanceof Round) {
        return false;
      }

      $expectedScores = new CountExpectedScores($this);
      $expectectedScoresCount = $expectedScores->run();
      //dd($expectectedScoresCount);

      $actualScoresCount = RawScore::where('round_id', $this->id)->where('score','>',0)->count();
      //dd($actualScoresCount);

      if ($expectectedScoresCount == 0 || $actualScoresCount < $expectectedScoresCount) {
        $roundIsMissingScores = true;
      } else {
        $roundIsMissingScores = false;
      }

      return $roundIsMissingScores;
    }

    public function isNewRound()
    {
      return strcmp($this->created_at, $this->updated_at) === 0;
    }


    public function getRatings(){
      if(!empty($this->ratings)){
        return $this->ratings;
      }

      return $this->ratings = (new Ratings($this))->all()->sortBy(function($rating){
        return $rating['earned_score'];
      });
    }
}
