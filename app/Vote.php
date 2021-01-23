<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
  protected $table = 'vote_results';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'audience_id', 'vote_id', 'vote_count', 'votes','premium_votes'
  ];

  protected $casts = [
    'votes' => 'array',
    'premium_votes' => 'array'
  ];

  /**
   * get choirs vote
   */
  public function choir()
  {
    return $this->hasOne('App\Choir', 'id', 'vote_id');
  }

  /**
   * Get performer
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function performer()
  {
    return $this->hasOne('App\Performer', 'id', 'vote_id');
  }

  /**
   * Get Vote
   *
   * @param $audienceId
   * @param $voteId
   * @return mixed
   */
  public static function getVote($audienceId, $voteId)
  {
    return Vote::where('audience_id', $audienceId)->where('vote_id', $voteId)->first();
  }

}
