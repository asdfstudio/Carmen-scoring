<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audience extends Model
{
    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];

    protected $fillable = [
        'competition_id',
        'division_id',
        'alias_name',
        'is_dark',
        'banner_type',
        'banner_upload',
        'banner_embed',
        'is_required_login',
        'social',
        'list_of_votes',
        'disable_vote',
        'limit_result',
        'is_premium_vote',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
      'social' => 'array',
      'list_of_votes' => 'array'
    ];

  public function division()
    {
        return $this->belongsTo('App\Division');
    }
    public function competition()
    {
        return $this->belongsTo('App\Competition');
    }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
    public function votes()
    {
      return $this->hasMany('App\Vote');
    }
}
