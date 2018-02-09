<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caption extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['name', 'color_id'];

    /*protected $attributes = [
      'slug' => 'overall'
    ];*/


		public function criteria()
		{
			return $this->hasMany('App\Criterion');
		}


		public function judges()
    {
        return $this->belongsToMany('App\Judge','division_judge');
    }

    public function getSlugAttribute()
    {
      return str_slug($this->name);
    }

    public function slug()
    {
      return str_slug($this->name);
    }

    public function scopeForSheet($query, $sheet = false)
    {
      if (!$sheet) return false;
      
      $raw = $query->whereIn('id', $sheet->caption_ids)->get();

      $desiredOrder = $sheet->caption_sort_order;

      $ordered = $raw->sort(function($a, $b) use ($desiredOrder) {
        $pos_a = array_search($a->id, $desiredOrder);
        $pos_b = array_search($b->id, $desiredOrder);
        return $pos_a - $pos_b;
      });

      return $ordered;
    }


    public function scopeForDivision($query, $division)
    {
      $raw = $query->where('division_id', $division->id)->get();

      $raw = collect();
      return $raw;

      /*$desiredOrder = $division->sheet->caption_sort_order;

      $ordered = $raw->sort(function($a, $b) use ($desiredOrder) {
        $pos_a = array_search($a->id, $desiredOrder);
        $pos_b = array_search($b->id, $desiredOrder);
        return $pos_a - $pos_b;
      });

      return $ordered;*/
    }

    public function orderByDivisionCaption($quer)
    {

    }


    public function getBackgroundCssAttribute()
    {
      return 'background-color-' . $this->color_id;
    }

    public function getLighterBackgroundCssAttribute()
    {
      return 'lighter-background-color-' . $this->color_id;
    }

    public function getDarkerBackgroundCssAttribute()
    {
      return 'darker-background-color-' . $this->color_id;
    }

    public function getTextCssAttribute()
    {
      return 'text-color-' . $this->color_id;
    }

    public function getBorderCssAttribute()
    {
      return 'border-color-' . $this->color_id;
    }

    public function getBorderLeftCssAttribute()
    {
      return 'border-left-color-' . $this->color_id;
    }
}
