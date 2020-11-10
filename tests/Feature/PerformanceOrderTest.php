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

use App\Events\PerformanceOrderChanged;
use App\Events\DivisionChoirCreated;
use App\Events\DivisionChoirRemoved;

class PerformanceOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Only run this on a testing environment
        if (\App::environment() != 'testing') {
            $this->markTestSkipped();
        }
    }

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
        event(new PerformanceOrderChanged($round));
        $this->assertEquals(['A'], $this->getPerformanceOrder($round));

        // When a choir B is added to a round, does it get performanceOrder set #2?
        $division->choirs()->save($bChoir);
        event(new DivisionChoirCreated($division, $bChoir));
        event(new PerformanceOrderChanged($round));
        $this->assertEquals(['A', 'B'], $this->getPerformanceOrder($round));

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
        event(new PerformanceOrderChanged($round));
        $this->assertEquals(['B', 'A'], $this->getPerformanceOrder($round));

        // Add  a choir C in another division. Is it order #3 in the round?
        $division2 = factory(Division::class)->create(['round_id' => $round]);
        $division2->choirs()->save($cChoir);
        event(new DivisionChoirCreated($division2, $cChoir));
        event(new PerformanceOrderChanged($round));
        $this->assertEquals(['B', 'A', 'C'], $this->getPerformanceOrder($round));

        // Add an earlier scheduleItem for the third item. Numbers swapped?
        $cItem = factory(ScheduleItem::class)->create([
            'schedule_id' => $schedule,
            'division_id' => $division2,
            'choir_id' => $cChoir,
            'scheduled_time' => '09:30:00',
        ]);
        event(new PerformanceOrderChanged($round));
        $this->assertEquals(['C', 'B', 'A'], $this->getPerformanceOrder($round));

        // Delete choir A. Is the order now B-C?
        $division->choirs()->detach($aChoir);
        event(new DivisionChoirRemoved($division, $aChoir));
        event(new PerformanceOrderChanged($round));
        $this->assertEquals(['C', 'B'], $this->getPerformanceOrder($round));

        // Remove the scheduleItem for B. Still B-C?
        $bItem->delete();
        event(new PerformanceOrderChanged($round));
        $this->assertEquals(['C', 'B'], $this->getPerformanceOrder($round));
    }

    /**
     * Given a round, return an array of choir names indexed by performance order
     */
    private function getPerformanceOrder(Round $round)  {
        $choirs = DB::table('choirs AS c')
            ->leftJoin('choir_division AS cd', 'c.id', '=', 'cd.choir_id')
            ->join('divisions AS d', 'd.id', '=', 'cd.division_id')
            ->where('d.round_id', $round->id)
            ->orderBy('cd.performance_order', 'asc')
            ->select('c.name', 'cd.performance_order');

        return $choirs->get()->pluck('name')->toArray();
    }
}
