<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoloDivision extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
      'name',
      'sheet_id',
      'max_performers',
      'category_1',
      'category_2'
    ];

    public function competition()
		{
			return $this->belongsTo('App\Competition');
		}

    public function judges()
    {
        return $this->belongsToMany('App\Judge', 'solo_judge');
    }

    public function performers()
    {
        return $this->hasMany('App\Performer');
    }


		public function sheet()
		{
			return $this->belongsTo('App\Sheet');
		}


    public function getStatusAttribute()
    {
      if($this->is_completed)
			{
        if($this->is_published)
          return 'Finalized / Published';
        else
				  return 'Completed';
			}
			else
			{
				return 'Active';
			}
    }

    public function getStatusSlugAttribute()
		{
			if($this->is_completed)
			{
        if($this->is_published)
          return 'finalized';
        else
				  return 'completed';
			}
			else
			{
				return 'active';
			}
		}

    public function status_label($class_attr = false)
    {
      $class_array = ['label', 'status', $this->status_slug];

      if($class_attr)
        $class_array[] = $class_attr;

      $class = implode(' ', $class_array);

      return '<span class="'.$class.'">'.$this->status.'</span>';
    }


    public function scopeCompleted($query)
		{
			return $query->where('is_completed', 1);
		}


    public function scopePublished($query)
		{
			return $query->where('is_published', 1);
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


    public function completeScoring()
    {
      $this->is_scoring_active = false;
      $this->is_completed = true;
      $this->is_published = false;
      return $this->save();
    }

    public function finalizeScoring()
    {
      $this->is_published = true;
      $this->is_scoring_active = false;
      $this->is_completed = true;

      if($this->access_code == false)
      {
        $this->access_code = strtoupper(str_random(8));
      }

      return $this->save();
    }
}
