<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sheet extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['name', 'caption_sort_order', 'is_retired'];

    protected $casts = [
      'caption_sort_order' => 'array'
    ];

    /*protected $attributes = [
      'caption_sort_order' => []
    ];*/


		public function divisions()
		{
			return $this->hasMany('App\Division');
		}

    /*public function captions()
    {
      //$this->load('criteria');
      //dd($this->criteria);
      return $this->belongsToMany('App\Criterion')->distinct('caption_id');
      //dd($x);
      //return $this->belongsToMany('App\Criterion')->unique('caption_id')->pluck('caption');
      //return $this->criteria->unique('caption_id')->pluck('caption');
      //return $this->criteria()->select(DB::raw('distinct caption_id'));
      //return $this->criteria()->pluck('caption_id')->toArray();
      //return $this->hasManyThrough('App\Caption', 'App\Criterion');
    }*/

    /*public function getCaptionsAttribute()
    {
      return $this->criteria->unique('caption_id')->pluck('caption');
    }*/


    /*public function abb()
    {
      return $this->hasMany('App\Caption', 'App\Criterion');
      return Caption::whereIn('id', $this->caption_ids)->get();
    }*/

		public function criteria()
    {
        return $this->belongsToMany('App\Criterion')->withPivot('sequence')->orderBy('sequence', 'asc');
    }

    public function getCaptionIdsAttribute()
    {
      $caption_ids = $this->criteria->unique('caption_id')->pluck('caption_id')->toArray();

      return $caption_ids;
    }

    /*public function getCaptionSortOrderAttribute($value)
    {
      return $value ? $value : [];
    }*/

    public function getMaxScoreAttribute()
    {
      return $this->criteria()->sum('max_score');
    }

    public function getWeightedMaxScoreAttribute()
    {
      $musicScore = 1.5 * $this->criteria()->where('criteria.caption_id', 1)->sum('max_score');
      $nonMusicScore = 1 * $this->criteria()->where('criteria.caption_id', 1)->sum('max_score');
      return $musicScore + $nonMusicScore;
    }
}
