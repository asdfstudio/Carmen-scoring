<?php

namespace Tests\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoundPageTest  extends TestCase
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

    public function testDivisionPage()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $division = \App\Division::with(['competition', 'round'])->first();
        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/round/' . $division->round->id)
            ->assertSuccessful();
    }
}
