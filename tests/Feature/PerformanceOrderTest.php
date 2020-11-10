<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use App\Round;
use App\Division;
use App\Choir;
use App\Schedule;
use App\ScheduleItem;

use App\Events\DivisionChoirCreated;
use App\Events\DivisionChoirRemoved;

class PerformanceOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the UpdatePerformanceOrder listener.
     *
     * @return void
     */
    public function testPerformanceOrder()
    {
        // Seed the database to create a basic competition, round, division
        $round = factory(Round::class)->create();
        $division = factory(Division::class)->create(['round_id' => $round]);

        $aChoir = factory(Choir::class)->create(['name' => 'A']);
        $bChoir = factory(Choir::class)->create(['name' => 'B']);
        $cChoir = factory(Choir::class)->create(['name' => 'C']);

        // When a choir A is added to a round, does it get a performanceOrder set? #1?
        $division->choirs()->save($aChoir);
        event(new DivisionChoirCreated($division, $aChoir));
        $this->assertEquals(['A'], $this->getOrderedChoirNames($round));
        $this->assertEquals(1, $this->getPerformanceOrder($aChoir, $round));

        // When a choir B is added to a round, does it get performanceOrder set #2?
        $division->choirs()->save($bChoir);
        event(new DivisionChoirCreated($division, $bChoir));
        $this->assertEquals(['A', 'B'], $this->getOrderedChoirNames($round));
        $this->assertEquals(2, $this->getPerformanceOrder($bChoir, $round));

        // Create a ScheduleItem for the choir B. Is the order now B-A?
        $schedule = factory(Schedule::class)->create([
            'name' => 'Performance Order Testing',
            'competition_id' => $round->competition
        ]);

        $bItem = factory(ScheduleItem::class)->create([
            'schedule_id' => $schedule,
            'division_id' => $division,
            'choir_id' => $bChoir,
            'scheduled_time' => '10:00:00',
        ]);
        $this->assertEquals(['B', 'A'], $this->getOrderedChoirNames($round));
        $this->assertEquals(2, $this->getPerformanceOrder($aChoir, $round));

        // Add  a choir C in another division. Is it order #3 in the round?
        $division2 = factory(Division::class)->create(['round_id' => $round]);
        $division2->choirs()->save($cChoir);
        event(new DivisionChoirCreated($division2, $cChoir));
        $this->assertEquals(['B', 'A', 'C'], $this->getOrderedChoirNames($round));
        $this->assertEquals(3, $this->getPerformanceOrder($cChoir, $round));

        // Add an earlier scheduleItem for the third item. Order swapped?
        $cItem = factory(ScheduleItem::class)->create([
            'schedule_id' => $schedule,
            'division_id' => $division2,
            'choir_id' => $cChoir,
            'scheduled_time' => '09:30:00',
        ]);
        $this->assertEquals(['C', 'B', 'A'], $this->getOrderedChoirNames($round));
        $this->assertEquals(3, $this->getPerformanceOrder($aChoir, $round));

        // Delete choir C. Is the order now B-A?
        $division2->choirs()->detach($cChoir);
        event(new DivisionChoirRemoved($division, $cChoir));
        $this->assertEquals(['B', 'A'], $this->getOrderedChoirNames($round));

        // Remove the scheduleItem for B. Still B-A?
        $bItem->delete();
        $this->assertEquals(['B', 'A'], $this->getOrderedChoirNames($round));
        $this->assertEquals(2, $this->getPerformanceOrder($aChoir, $round));
    }

    /**
     * Given a round, return an array of choir names indexed by performance order
     */
    private function getOrderedChoirNames(Round $round)  {
        $choirs = $this->getChoirDivisionBuilder($round)
            ->select('c.name');
        return $choirs->get()->pluck('name')->toArray();
    }

    /**
     * Given a choir and a round, return the performance_order
     */
    private function getPerformanceOrder(Choir $choir, Round $round) {
        $choir = $this->getChoirDivisionBuilder($round)
            ->where('c.id', $choir->id)
            ->select('cd.performance_order')
            ->first();
        return $choir->performance_order;
    }

    /**
     * Query builder used in multiple methods
     */
    private function getChoirDivisionBuilder($round) {
        return DB::table('choirs AS c')
            ->leftJoin('choir_division AS cd', 'c.id', '=', 'cd.choir_id')
            ->join('divisions AS d', 'd.id', '=', 'cd.division_id')
            ->where('d.round_id', $round->id)
            ->orderBy('cd.performance_order', 'asc');
    }

}
