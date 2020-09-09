<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoundChangeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Only run this on a testing environment
        if (\App::environment() != 'testing') {
            $this->markTestSkipped();
        }

        $this->seed('RoundChangeSeeder');
    }

    public function testCompetitionPage()
    {
        $user = \App\User::firstWhere('username', 'test-admin');
        $this->actingAs($user)
            ->get('/organizer/competition/'. \App\Competition::first()->id)
            ->assertSuccessful();
    }

    public function testJudgeScores()
    {
        $easyDiv = \App\Division::firstWhere('name', 'Oddly Easy Division');
        $choir = \App\Choir::firstWhere('name', 'Choir 5');

        $score = \App\RawScore::where('division_id', $easyDiv->id)
            ->where('choir_id', $choir->id)
            ->where('judge_id', \App\Judge::firstWhere('last_name', 'Even')->id)
            ->get()->first();

        $this->assertEquals(6.0, $score->score, 'Even\'s Oddly Easy Score is not right');

        $score = \App\RawScore::where('division_id', $easyDiv->id)
            ->where('choir_id', $choir->id)
            ->where('judge_id', \App\Judge::firstWhere('last_name', 'Meanie')->id)
            ->get()->first();

        $this->assertEquals(5.0, $score->score, 'Meanie\'s Oddly Easy Score is not right');

        $score = \App\RawScore::where('division_id', $easyDiv->id)
            ->where('choir_id', $choir->id)
            ->where('judge_id', \App\Judge::firstWhere('last_name', 'Nicely')->id)
            ->get()->first();

        $this->assertEquals(7.0, $score->score, 'Nicely\'s Oddly Easy Score is not right');
    }

}
