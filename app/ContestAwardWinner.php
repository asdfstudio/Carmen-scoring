<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContestAwardWinner extends Model
{
    protected $table = 'competition_award';

    public function competition()
    {
        return $this->belongsTo('App\Competition');
    }
}
