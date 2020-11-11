<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use App\Events\PerformanceOrderChanged;

class ScheduleItem extends Model
{

    protected $fillable = ['division_id', 'choir_id', 'name', 'performance_order', 'scheduled_time'];

    protected static function booted()
    {
        static::addGlobalScope('performanceOrder', function(Builder $builder) {
            $builder->orderBy('performance_order', 'asc');
        });

        static::deleted(function ($item) {
            event(new PerformanceOrderChanged($item->round));
        });

        static::saved(function ($item) {
            event(new PerformanceOrderChanged($item->round));
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

    public function round()
    {
        return $this->hasOneThrough('App\Round', 'App\Division', 'id', 'id', 'division_id', 'round_id');
    }

    public function choir()
    {
        return $this->belongsTo('App\Choir');
    }

    public function setScheduledTimeAttribute($value)
    {
        $this->attributes['scheduled_time'] = date("Y-m-d G:i", strtotime($value));
    }

}
