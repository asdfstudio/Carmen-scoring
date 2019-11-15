<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Recording extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $fillable = ['division_id','round_id', 'choir_id', 'judge_id', 'url'];


	public function getUrlAttribute($value)
    {
        return ($value)?Storage::disk('s3')->url($value):'';
    }

    /**
     * Get all of the video's comments.
     */
    public function judge()
    {
        return $this->belongsTo('App\Judge');
    }

}
