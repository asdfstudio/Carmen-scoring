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

    public function testRoundPageTitle()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $division = \App\Division::with(['competition', 'round'])->first();
        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/round/' . $division->round->id)
            ->assertSeeText($division->round->name);
    }

    public function testRoundPageActiveStatus()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $division = \App\Division::with(['competition', 'round'])->first();

        // check Manage Divisions, Edit scoring , Edit judges button visibility
        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/round/' . $division->round->id)
            ->assertSeeTextInOrder(["Edit Scoring", "Edit Judges", "Manage Divisions"]);
    }

    public function testRoundPageFinalizedStatus()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $division = \App\Division::with(['competition', 'round'])->first();

        // change round status into finalized
        $division->is_published = 1;
        $division->save();

        // check Manage Divisions, Edit scoring , Edit judges button visibility
        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/round/' . $division->round->id)
            ->assertDontSeeText("Edit Scoring");

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/round/' . $division->round->id)
            ->assertDontSeeText("Edit Judges");

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/round/' . $division->round->id)
            ->assertDontSeeText("Manage Divisions");

    }
}
