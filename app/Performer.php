<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Performer extends Model
{

  protected $fillable = [
    'choir_id',
    'name',
    'gender',
    'total_score',
    'overall_place',
    'gender_place'
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


  public function getGenderNameAttribute()
  {
    if($this->gender == 'M')
      return 'Male';
    elseif($this->gender == 'F')
      return 'Female';

    return 'Not Set';
  }

  public function gender_label($class_attr = false)
  {
    $class_array = ['label', 'gender', strtolower($this->genderName)];

    if($class_attr)
      $class_array[] = $class_attr;

    $class = implode($class_array,' ');

    return '<span class="'.$class.'">'.$this->genderName.'</span>';
  }
}
