<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Performer extends Model
{

  protected $fillable = [
    'choir_id',
    'name',
    'category',
    'total_score',
    'overall_place',
    'category_place'
  ];

  public function soloDivision()
  {
    return $this->belongsTo('App\SoloDivision');
  }

  public function choir()
  {
    return $this->belongsTo('App\Choir');
  }

  public function scores()
  {
    return $this->hasMany('App\SoloRawScore');
  }

  public function comments()
  {
    return $this->morphMany('App\Comment', 'recipient');
  }

  public function getCategoryNameAttribute()
  {
    if($this->category == 1 AND $this->soloDivision)
      return $this->soloDivision->category_1;
    elseif($this->category == 2 AND $this->soloDivision)
      return $this->soloDivision->category_2;

    return 'Not Set';
  }

  public function category_label($class_attr = false)
  {
    $class_array = ['label', 'category', 'category-' . $this->category ];

    if($class_attr)
      $class_array[] = $class_attr;

    $class = implode(' ', $class_array);

    return '<span class="'.$class.'">'.$this->categoryName.'</span>';
  }

  public function vote()
  {
    return $this->hasMany('App\Vote','vote_id','id');
  }

  /**
   * Get vote from votes table
   *
   * @param $audienceId
   * @return mixed
   */
  public function votes($audienceId)
  {
    return Vote::getVote($audienceId, $this->id);
  }
}
