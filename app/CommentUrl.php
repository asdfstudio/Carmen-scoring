<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentUrl extends Model
{
    protected $fillable = ['competition_id', 'choir_id', 'access_code'];

    public function competition()
		{
			return $this->belongsTo('App\Competition');
		}

    public function choir()
    {
      return $this->belongsTo('App\Choir');
    }

    public function setAccessCodeAttribute($value)
    {
      $this->attributes['access_code'] = strtolower($value);
    }
}
