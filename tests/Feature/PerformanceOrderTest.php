<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Round;
use App\Division;
use App\Choir;
use App\Schedule;
use App\ScheduleItem;

use App\Events\PerformanceOrderChanged;

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

        $this->seed('DatabaseSeeder');
    }

    /**
     * Test the UpdatePerformanceOrder listener.
     *
     * @return void
     */
    public function testPerformanceOrder()
    {
        // Seed the database to create a basic competition, round, division
        $round = Round::firstWhere('name', 'Prelims');
        $division = Division::firstWhere('name', 'Demo High School Division 1');

        $aChoir = factory(Choir::class)->create(['name' => 'A']);
        $bChoir = factory(Choir::class)->create(['name' => 'B']);
        $cChoir = factory(Choir::class)->create(['name' => 'C']);

        // When a choir A is added to a round, does it get a performanceOrder set? #1?
        $division->choirs()->save($aChoir);
        // Manually fire the event to test
        event(new PerformanceOrderChanged($round));
        // $this->assertEquals(['A'], $this->getPerformanceOrder($round));

        // When a choir B is added to a round, does it get performanceOrder set #2?
        $division->choirs()->save($bChoir);
        // Manually fire the event to test
        event(new PerformanceOrderChanged($round));
        // $this->assertEquals(['A', 'B'], $this->getPerformanceOrder($round));

        // Create a ScheduleItem for the choir B. Is the order now B-A?
        $schedule = factory(Schedule::class)->create([
            'name' => 'Performance Order Testing',
            'competition_id' => $round->competition
        ]);

        $bItem = factory(ScheduleItem::class)->create([
            'schedule_id' => $schedule,
            'division_id' => $division->id,
            'choir_id' => $bChoir,
            'scheduled_time' => '10:00:00',
        ]);
        // $this->assertEquals(['B', 'A'], $this->getPerformanceOrder($round));

        // Add  a choir C in another division. Is it order #3 in the round?
        $division2 = Division::firstWhere('name', 'Demo High School Division 2');
        $division2->choirs()->save($cChoir);
        // $this->assertEquals(['B', 'A', 'C'], $this->getPerformanceOrder($round));

        // Add an earlier scheduleItem for the first item. Numbers swapped?
        $cItem = factory(ScheduleItem::class)->create([
            'schedule_id' => $schedule,
            'division_id' => $division2->id,
            'choir_id' => $bChoir,
            'scheduled_time' => '09:30:00',
        ]);
        // $this->assertEquals(['C', 'A', 'B'], $this->getPerformanceOrder($round));
        // Delete choir A. Is the order now B-C?
        $division->choirs()->detach($aChoir);
        // $this->assertEquals(['B', 'C'], $this->getPerformanceOrder($round));

        // Remove the scheduleItem for B. Still B-C?
        $bItem->delete();
        // $this->assertEquals(['B', 'C'], $this->getPerformanceOrder($round));
    }

    /**
     * Given a round, return an array of choir names indexed by performance order
     */
    private function getPerformanceOrder(Round $round)  {

        // Return an array of the choir names for a given round, in order
        return [];
    }
}
