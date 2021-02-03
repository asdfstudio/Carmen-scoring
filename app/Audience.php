<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audience extends Model
{
    use SoftDeletes;

    protected $table = 'vote_settings';

    protected $dates = ['created_at','updated_at','deleted_at'];

    protected $fillable = [
        'competition_id',
        'division_id',
        'alias_name',
        'is_dark',
        'banner_type',
        'banner_upload',
        'banner_embed',
        'list_of_votes',
        'limit_result',
        'is_enabled',
        'is_premium_vote',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'list_of_votes' => 'array'
    ];

    public function competition()
    {
        return $this->belongsTo('App\Competition');
    }

    /**
     * Audiences can belong to a Round or a SoloDivision
     */
    public function audienceable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany('App\Vote');
    }
}
