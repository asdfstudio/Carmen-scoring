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

        $choirs = DB::table('choir_round AS cr')
            ->join('divisions AS d', 'cr.round_id', '=', 'd.round_id')
            ->join('choir_division as cd', function($join) {
                $join->on('cd.division_id', '=', 'd.id')->on('cr.choir_id', '=', 'cd.choir_id');
            })
            ->leftJoin('schedule_items AS si', function($join) {
                $join->on('si.division_id', '=', 'cd.division_id')->on('si.choir_id', '=', 'cr.choir_id');
            })
            ->where('cr.round_id', '=', $round_id)
            // scheduled_time opposite descending, which puts nulls last and earlier times first
            ->orderByRaw('-si.scheduled_time desc')
            // New choir_divisions have a performance_order of 0, which should be sorted last (~0 is bitwise-not-zero, AKA big as it gets)
            ->orderByRaw('CASE cr.performance_order WHEN 0 THEN ~0 ELSE cr.performance_order END ASC')
            ->select('cr.*');


        // If an item is not its index (i+1 becuase of 0-based arrays), update it.
        $needUpdate = $choirs->get()->map(function($item, $index) {
            $item->new_performance_order = $index + 1;
            return $item;
        })->filter(function($item) {
            return $item->performance_order != $item->new_performance_order;
        });

        $query = 'UPDATE choir_round cr SET cr.performance_order = :order where cr.round_id = :round and cr.choir_id = :choir';

        $needUpdate->each(function($item, $key) use ($query) {
            DB::update($query, [
                'order' => $item->new_performance_order,
                'round' => $item->round_id,
                'choir' => $item->choir_id
            ]);
        });
    }
}
