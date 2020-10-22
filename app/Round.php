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

    protected $fillable = [
        'name',
        'sequence',
        'caption_weighting_id',
        'scoring_method_id',
        'sheet_id',
    ];

    protected $ratings;

    public function competition()
    {
        return $this->belongsTo('App\Competition');
    }

    public function divisions()
    {
        return $this->hasMany('App\Division');
    }

    public function judges()
    {
        return $this->belongsToMany('App\Judge', 'round_judge')->withPivot('caption_id');
    }

    public function penalties()
    {
        return $this->belongsToMany('App\Penalty', 'choir_penalty')->withPivot('choir_id');
    }

    // TODO: See if the migration affected the morph here. Might need to update the subject_type columns.
    public function feedback()
    {
        return $this->morphMany('App\Comment', 'subject');
    }

    public function sheet()
    {
        return $this->belongsTo('App\Sheet');
    }

    public function scoringMethod()
    {
        return $this->belongsTo('App\ScoringMethod');
    }

    public function captionWeighting()
    {
        return $this->belongsTo('App\CaptionWeighting');
    }

    public function status()
    {
        $total = $this->divisions()->count();

        if ($this->divisions()->published()->count() == $total && $total != 0) {
            return 'Finalized';
        } elseif ($this->divisions()->completed()->count() == $total && $total != 0) {
            return 'Completed';
        } else {
            return 'Active';
        }
    }

    public function status_slug()
    {
        return strtolower($this->status());
    }

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

    // TODO: Make this more efficient with its own query
    public function isMissingScores()
    {
        foreach($this->divisions as $division) {
            if ($division->isMissingScores()) {
                return true;
            }
        }
        return false;
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
