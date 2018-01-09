<?php

namespace App;

use App\Scopes\OrderByNameScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Award extends Model
{
  use SoftDeletes;
  use RestrictsOrganization;

  protected $dates = ['deleted_at'];

  protected $fillable = ['name', 'description', 'organization_id'];

  protected static function boot()
  {
      parent::boot();

      static::addGlobalScope(new OrderByNameScope);
  }


  public function organization()
  {
    return $this->belongsTo('App\Organization');
  }

  public function divisions()
  {
    return $this->belongsToMany('App\Division', 'division_award')->withPivot('choir_id', 'recipient');
  }


  public function choirs()
  {
    return $this->belongsToMany('App\Choir', 'division_award')->withPivot('recipient', 'sponsor');
  }


  public function winner()
  {
    return $this->hasOne('App\AwardWinner', 'award_id');
  }



  public function owner()
  {
    return $this->organization_id ? 'Custom' : 'Standard';
  }

  public function getChoirAttribute()
  {
    return $this->choirs->first();
  }
}
