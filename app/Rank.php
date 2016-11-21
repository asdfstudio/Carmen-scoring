<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
  protected $fillable = ['standing_id', 'choir_id', 'raw_rank', 'final_rank'];

  public function standing()
  {
    return $this->belongsTo('App\Standing');
  }
}
