<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable =  [
      'name',
      'caption_weighting_id',
      'scoring_method_id',
      'sheet_id',
      'combo_award_count',
      'music_award_count',
      'show_award_count',
      'overall_award_count',
      'overall_award_sponsors',
      'music_award_sponsors',
      'show_award_sponsors',
      'combo_award_sponsors'
    ];

    protected $casts = [
      'overall_award_sponsors' => 'array',
      'music_award_sponsors' => 'array',
      'show_award_sponsors' => 'array',
      'combo_award_sponsors' => 'array'
    ];

		/*public function organization()
		{
			// There is no belongsToThrough method
		}*/

		public function competition()
		{
			return $this->belongsTo('App\Competition');
		}


		public function judges()
    {
        return $this->belongsToMany('App\Judge')->withPivot('caption_id');
    }

		public function choirs()
    {
        return $this->belongsToMany('App\Choir');
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


		public function penalties()
    {
        return $this->belongsToMany('App\Penalty');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Award', 'division_award')->withPivot( 'choir_id', 'recipient', 'sponsor');
    }

		public function rounds()
    {
        return $this->hasMany('App\Round');
    }


    public function standings()
    {
      return $this->hasMany('App\Standing');
    }


    public function scopeCompleted($query)
		{
			return $query->where('is_completed', 1);
		}


    public function scopePublished($query)
		{
			return $query->where('is_published', 1);
		}


    public function status()
    {
      if($this->is_completed)
			{
        if($this->is_published)
          return 'Finalized / Published';
        else
				  return 'Completed';
			}
			/*elseif($this->is_scoring_active)
			{
				return 'Active';
			}*/
			else
			{
				return 'Active';
			}
    }

    public function status_slug()
		{
			if($this->is_completed)
			{
        if($this->is_published)
          return 'finalized';
        else
				  return 'completed';
			}
			/*elseif($this->is_scoring_active)
			{
				return 'active';
			}*/
			else
			{
				return 'active';
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

      $class = implode($class_array,' ');

      return '<span class="'.$class.'">'.$this->status.'</span>';
    }


    public function activateScoring()
    {
      $this->is_scoring_active = true;
      $this->is_completed = false;
      $this->is_published = false;
      return $this->save();
    }

    public function deactivateScoring()
    {
      $this->is_scoring_active = false;
      $this->is_completed = false;
      $this->is_published = false;
      return $this->save();
    }

    public function reactivateScoring()
    {
      $this->is_scoring_active = true;
      $this->is_completed = false;
      $this->is_published = false;
      return $this->save();
    }

    public function completeScoring()
    {
      $this->is_scoring_active = false;
      $this->is_completed = true;
      $this->is_published = false;
      $saved = $this->save();

      // Update all rounds
      $this->rounds()->update(['is_completed' => true]);

      return $saved;
    }

    public function finalizeScoring()
    {
      $this->is_published = true;
      $this->is_scoring_active = false;
      $this->is_completed = true;
      $this->access_code = strtoupper(str_random(8));
      return $this->save();
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
    }*/
}
