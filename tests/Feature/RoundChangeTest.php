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

        $this->seed('RoundChangeSeeder');
    }

    public function testCompetitionPage()
    {
        $user = \App\User::firstWhere('username', 'test-admin');
        $this->actingAs($user)
            ->get('/organizer/competition/'. \App\Competition::first()->id)
            ->assertSuccessful();

    }

}
