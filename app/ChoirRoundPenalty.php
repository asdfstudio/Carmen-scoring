<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChoirRoundPenalty extends Model
{
    protected $table = 'choir_penalty';

    public function penalty()
		{
			return $this->belongsTo('App\Penalty');
		}
}
