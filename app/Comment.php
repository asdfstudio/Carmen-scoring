<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{

		protected $fillable = ['judge_id', 'choir_id', 'recipient_type', 'recipient_id', 'subject_type', 'subject_id', 'comments'];

		public function judge()
		{
			return $this->belongsTo('App\Judge');
		}

		/*public function choir()
		{
			return $this->belongsTo('App\Choir');
		}*/

        public function criterion()
		{
			return $this->belongsTo('App\Criterion', 'recipient_id', 'id');
		}

		public function recipient()
		{
			return $this->morphTo();
		}

		public function subject()
		{
			return $this->morphTo();
		}

		public function recordings()
		{

			return $this->hasMany('App\Recording','judge_id','judge_id');
		}

}
