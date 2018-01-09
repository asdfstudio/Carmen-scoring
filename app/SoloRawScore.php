<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoloRawScore extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  protected $fillable = ['solo_division_id', 'performer_id', 'judge_id', 'criterion_id', 'score'];


  public function performer()
  {
      return $this->belongsTo('App\Performer');
  }

  public function judge()
  {
      return $this->belongsTo('App\Judge');
  }

  public function criterion()
  {
      return $this->belongsTo('App\Criterion');
  }

  public function getScoreAttribute($value)
  {
    return number_format($value, 1);
  }
}
