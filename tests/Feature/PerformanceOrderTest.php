<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Round;
use App\Choir;
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

        $aChoir = factory(Choir::class, ['name' => 'A']);
        $bChoir = factory(Choir::class, ['name' => 'B']);
        $cChoir = factory(Choir::class, ['name' => 'C']);

        // When a choir A is added to a round, does it get a performanceOrder set? #1?
        $division->choirs()->attach($aChoir);
        $division->choirs()->save();
        // Manually fire the event to test
        event(new PerformanceOrderChanged($round));
        $this->assertEquals(['A'], $this->getPerformanceOrder($round));

        // When a choir B is added to a round, does it get performanceOrder set #2?
        $division->choirs()->attach($bChoir);
        $division->choirs()->save();
        // Manually fire the event to test
        event(new PerformanceOrderChanged($round));
        $this->assertEquals(['A', 'B'], $this->getPerformanceOrder($round));

        // Create a ScheduleItem for the choir B. Is the order now B-A?
        //
        $schedule = new Schedule()
        // Add an earlier scheduleItem for the first item. Numbers swapped?
        // Add  a choir C in another division. Is it order #3 in the round?
        // Delete choir A. Is the order now B-C?
        // Remove the scheduleItem for B. Still B-C?
    }

    /**
     * Given a round, return an array of choir names indexed by performance order
     */
    private function getPerformanceOrder(Round $round)  {

        // Return an array of the choir names for a given round, in order
    }
}
