<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoundAwardWinner extends Model
{
    protected $table = 'round_award';

    public function round()
    {
        return $this->belongsTo('App\Round');
    }

    public function choir()
    {
        return $this->belongsTo('App\Choir');
    }
}
