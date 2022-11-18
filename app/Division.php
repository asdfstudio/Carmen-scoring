<?php

namespace App;

use App\Scopes\OrderByNameScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

use App\Carmen\Ratings;
use App\Carmen\CountExpectedScores;

class Division extends Model
{
    use SoftDeletes;

    // TODO: Is this neccessary if we've already used SoftDeletes?
    protected $dates = ['deleted_at'];

    protected $fillable =  [
        'name',
        'max_choirs',
        'combo_award_count',
        'music_award_count',
        'show_award_count',
        'overall_award_count',
        'overall_award_sponsors',
        'music_award_sponsors',
        'show_award_sponsors',
        'combo_award_sponsors',
        'rating_system',
        'round_id'
    ];

    protected $casts = [
        'overall_award_sponsors' => 'array',
        'music_award_sponsors' => 'array',
        'show_award_sponsors' => 'array',
        'combo_award_sponsors' => 'array',
        'rating_system' => 'array',
        'is_scoring_active' => 'boolean',
        'is_completed' => 'boolean',
        'is_published' => 'boolean'
    ];

    protected $ratings;

    protected static function booted()
    {
        static::addGlobalScope(new OrderByNameScope);
    }

    public function choirs()
    {
        return $this->belongsToMany('App\Choir')->withPivot('receives_rankings', 'receives_ratings');
    }

    public function judges()
    {
        if ($this->round) {
            return $this->round->judges;
        }
    }

    public function penalties()
    {
        return $this->belongsToMany('App\Penalty');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Award', 'division_award')->withPivot( 'choir_id', 'recipient', 'sponsor');
    }

    public function awardSettings()
    {
        return $this->hasMany('App\DivisionAwardSetting');
    }

    public function round()
    {
        return $this->belongsTo('App\Round');
    }

    public function competition()
    {
        return $this->hasOneThrough('App\Competition', 'App\Round', 'id', 'id', 'round_id', 'competition_id');
    }

    public function sheet()
    {
        return $this->hasOneThrough('App\Sheet', 'App\Round', 'id', 'id', 'round_id', 'sheet_id');
    }

    public function standings()
    {
        return $this->hasMany('App\Standing');
    }

    public function scopeActive($query)
    {
        return $query->where('is_scoring_active', 1);
    }

    public function scopeIncomplete($query)
    {
        return $query->where('is_completed', 0);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', 1);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    /**
     * Statuses to be printed in labels
     */
    public function status()
    {
        if($this->is_completed) {
            return $this->is_published ? 'Finalized / Published' : 'Completed';
        } else {
            return $this->is_scoring_active ? 'Active' : 'Inactive';
        }
    }

    /**
     * Terse statuses used in CSS classes and string comparisons
     */
    public function status_slug()
    {
        if($this->is_completed) {
            return $this->is_published ? 'finalized' : 'completed';
        } else {
            return $this->is_scoring_active ? 'activated' : 'deactivated';
        }
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

    public function getFullNameAttribute()
    {
        return $this->round->name() . " round - " . $this->name();
    }

    public function getMaxChoirsTextAttribute()
    {
        return $this->max_choirs == 0 ? 'All' : $this->max_choirs;
    }

    public function activateScoring()
    {
        $this->is_scoring_active = true;
        $this->is_completed = false;
        $this->is_published = false;
        $saved = $this->save();

        return $saved;
    }

    public function deactivateScoring()
    {
        $this->is_scoring_active = false;
        $this->is_completed = false;
        $this->is_published = false;
        $saved = $this->save();

        return $saved;
    }

    public function reactivateScoring()
    {
        $this->is_scoring_active = true;
        $this->is_completed = false;
        $this->is_published = false;
        $saved = $this->save();

        return $saved;
    }

    public function completeScoring()
    {
        $this->is_scoring_active = false;
        $this->is_completed = true;
        $this->is_published = false;
        $saved = $this->save();

        return $saved;
    }

    // Finalize == Publishing
    public function finalizeScoring()
    {
        $this->is_published = true;
        $this->is_scoring_active = false;
        $this->is_completed = true;

        if($this->access_code == false) {
            $this->access_code = strtoupper(Str::random(8));
        }

        if(env('IS_WORKSHOP_ENABLED') == true) {
            $this->access_code = $this->id;
        }

        return $this->save();
    }

    public function getRatings(){
        if (!$this->ratings) {
            $this->ratings = new Ratings($this);
        }
        return $this->ratings->all();
    }

    public function isMissingScores() {
        $expectedScores = new CountExpectedScores($this);
        $expectectedScoresCount = $expectedScores->run();

        $actualScoresCount = RawScore::where('division_id', $this->id)->where('score','>',0)->count();

        if ($expectectedScoresCount == 0 || $actualScoresCount < $expectectedScoresCount) {
            $roundIsMissingScores = true;
        } else {
            $roundIsMissingScores = false;
        }

        return $roundIsMissingScores;
    }

    public function isNew()
    {
      return strcmp($this->created_at, $this->updated_at) === 0;
    }



/*
    public function setOverallAwardSponsorsAttribute($value)
    {
      return $this->attributes['overall_award_sponsors'] = array_values(array_filter(explode(PHP_EOL, $value)));
    }

    public function setMusicAwardSponsorsAttribute($value)
    {
      return array_values(array_filter(explode(PHP_EOL, $value)));
    }

    public function setShowAwardSponsorsAttribute($value)
    {
      return array_values(array_filter(explode(PHP_EOL, $value)));
    }

    public function setComboAwardSponsorsAttribute($value)
    {
      return array_values(array_filter(explode(PHP_EOL, $value)));
    }
 */
}
