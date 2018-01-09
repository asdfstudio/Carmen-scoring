<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sheet extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['name'];


		public function divisions()
		{
			return $this->hasMany('App\Division');
		}

    public function captions()
    {
      //return $this->criteria()->pluck('caption_id')->toArray();
      //return $this->hasManyThrough('App\Caption', 'App\Criterion');
    }

		public function criteria()
    {
        return $this->belongsToMany('App\Criterion');
    }

    public function getCaptionIdsAttribute()
    {
      $caption_ids = $this->criteria->unique('caption_id')->pluck('caption_id')->toArray();

      return $caption_ids;
    }
}
