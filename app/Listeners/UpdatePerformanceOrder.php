<?php

namespace App\Listeners;

use App\Events\PerformanceOrderChanged;
use App\Events\DivisionChoirCreated;
use App\Events\DivisionChoirRemoved;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdatePerformanceOrder
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Reorder the choir_division items according to any performance times.
     *
     * @param  $event
     * @return void
     */
    public function handle($event)
    {

        if ($event instanceof PerformanceOrderChanged) {
            $round_id = $event->round->id;
        } elseif ($event instanceof DivisionChoirCreated OR $event instanceof DivisionChoirRemoved) {
            $round_id = $event->division->round->id;
        }

        $choirs = DB::table('choir_division AS cd')
            ->join('divisions AS d', 'd.id', '=', 'cd.division_id')
            ->leftJoin('schedule_items AS si', function($join) {
                $join->on('cd.choir_id', '=', 'si.choir_id')->on('cd.division_id', '=', 'si.division_id');
            })
            ->where('d.round_id', '=', $round_id)
            // scheduled_time opposite descending, which puts nulls last and earlier times first
            ->orderByRaw('-si.scheduled_time desc')
            // New choir_divisions have a performance_order of 0, which should be sorted last (~0 is bitwise-not-zero, AKA big as it gets)
            ->orderByRaw('CASE cd.performance_order WHEN 0 THEN ~0 ELSE cd.performance_order END ASC')
            ->select('cd.*');

        // If an item is not its index (i+1 becuase of 0-based arrays), update it.
        $needUpdate = $choirs->get()->map(function($item, $index) {
            $item->new_performance_order = $index + 1;
            return $item;
        })->filter(function($item) {
            return $item->performance_order != $item->new_performance_order;
        });

        $query = 'UPDATE choir_division cd SET cd.performance_order = :order where cd.division_id = :division and cd.choir_id = :choir';

        $needUpdate->each(function($item, $key) use ($query) {
            DB::update($query, [
                'order' => $item->new_performance_order,
                'division' => $item->division_id,
                'choir' => $item->choir_id
            ]);
        });
    }
}
