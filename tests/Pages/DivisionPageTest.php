<?php

namespace Tests\Pages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DivisionPageTest  extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setup();
        $this->seed('PagesTestSeeder');
    }

    public function testDivisionPageTitle()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $division = \App\Division::with(['competition'])->first();

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertSeeText($division->name);
    }

    public function testDivisionInactiveStatus()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $division = \App\Division::with(['competition', 'round'])->first();

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertSeeText("Activate Scoring");

        // Don't show other buttons.
        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertDontSeeText("Complete Scoring");

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertDontSeeText("Pause Scoring");

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertDontSeeText("Reactivate Scoring");
    }

    public function testDivisionActiveStatus()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $division = \App\Division::with(['competition', 'round'])->first();

        // change division status into active
        $division->is_scoring_active = 1;
        $division->is_completed = 0;
        $division->save();

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertSeeText("Pause Scoring");

        // Don't show other buttons.
        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertDontSeeText("Complete Scoring");

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertDontSeeText("Activate Scoring");

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertDontSeeText("Reactivate Scoring");
    }


    public function testDivisionCompleteStatus()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $division = \App\Division::with(['competition', 'round'])->first();

        // change division status into completed
        $division->is_completed = 1;
        $division->save();

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertSeeText("Send scores and feedback");

        // Don't show other buttons.
        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertDontSeeText("Pause Scoring");

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertDontSeeText("Complete Scoring");

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertDontSeeText("Activate Scoring");

        $this->actingAs($user)
            ->get('/organizer/competition/' . $division->competition->id . '/division/' . $division->id)
            ->assertDontSeeText("Reactivate Scoring");
    }
}
