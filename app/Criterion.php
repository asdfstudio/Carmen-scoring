<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Criterion extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['caption_id', 'name', 'description', 'max_score'];

		public function sheets()
    {
        return $this->belongsToMany('App\Sheet');
    }

		public function caption()
		{
			return $this->belongsTo('App\Caption');
		}
}
