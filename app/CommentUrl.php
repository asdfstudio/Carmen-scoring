<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentUrl extends Model
{
    protected $fillable = ['competition_id', 'choir_id', 'recipient_type', 'recipient_id', 'access_code'];

    public function competition()
		{
			return $this->belongsTo('App\Competition')->withoutGlobalScope('organization');
		}

    public function choir()
    {
      return $this->belongsTo('App\Choir');
    }

    public function recipient()
		{
			return $this->morphTo();
		}

    public function setAccessCodeAttribute($value)
    {
      $this->attributes['access_code'] = strtolower($value);
    }
}
