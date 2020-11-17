<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Recording extends Model
{
  use SoftDeletes;

	protected $dates = ['deleted_at', 'created_at'];

	protected $fillable = ['division_id', 'round_id', 'choir_id', 'judge_id', 'url'];

	public function getUrlAttribute($path)
  {
    return ($path) ? Storage::disk('recordings')->url($path) : '';
  }

  public function judge()
  {
    return $this->belongsTo('App\Judge');
  }

  public function getNiceDate()
  {
    return date('M. j, Y \a\t h:i:s A (T)', strtotime($this->created_at));
  }
}
