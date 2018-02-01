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
