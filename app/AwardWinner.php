<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AwardWinner extends Model
{
  protected $table = 'division_award';

  public function division()
  {
    return $this->belongsTo('App\Division');
  }

  public function choir()
  {
    return $this->belongsTo('App\Choir');
  }
}
