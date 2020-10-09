<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ScheduleItem extends Model
{

    protected $fillable = ['round_id', 'choir_id', 'name', 'performance_order', 'scheduled_time'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('performanceOrder', function(Builder $builder) {
            $builder->orderBy('performance_order', 'asc');
        });
    }

    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }

    public function division()
    {
        return $this->belongsTo('App\Division');
    }

    public function choir()
    {
        return $this->belongsTo('App\Choir');
    }

    public function setScheduledTimeAttribute($value)
    {
        $this->attributes['scheduled_time'] = date("G:i", strtotime($value));
    }

}
