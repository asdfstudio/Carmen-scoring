<?php

namespace Tests\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompetitionPageTest  extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setup();
        if (\App::environment() != 'testing') {
            $this->markTestSkipped();
        }
        $this->seed('PagesTestSeeder');
    }

    public function testCompetitionPage()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $this->actingAs($user)
            ->get('/organizer/competition/'. \App\Competition::first()->id)
            ->assertSuccessful();
    }
}
