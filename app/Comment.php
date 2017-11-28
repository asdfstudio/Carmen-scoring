<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{

		protected $fillable = ['judge_id', 'choir_id', 'subject_type', 'subject_id', 'comments'];

		public function judge()
		{
			return $this->belongsTo('App\Judge');
		}

		public function choir()
		{
			return $this->belongsTo('App\Choir');
		}

		public function subject()
		{
			return $this->morphTo();
		}

}
