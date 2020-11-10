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
        $this->seed('PagesTestSeeder');
    }

    public function testCompetitionPageTitle()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $competition = \App\Competition::first();
        $this->actingAs($user)
            ->get('/organizer/competition/'. $competition->id)
            ->assertSeeText($competition->name);
    }

    public function testCompetitionPageArchivedStatus()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $competition = \App\Competition::first();

        // change competition status to archived
        $competition->is_archived = 1;
        $competition->is_completed = 1;
        $competition->save();

        // check the activate competition button show/hide when the status is archived
        $this->actingAs($user)
            ->get('/organizer/competition/'. $competition->id)
            ->assertSeeTextInOrder(["Archived", "Activate Competition"]);

        // check the Activate  competition button visibility
        $this->actingAs($user)
            ->get('/organizer/competition/'. $competition->id)
            ->assertDontSeeText("Close Competition");

        // check the Archive  competition button visibility
           $this->actingAs($user)
              ->get('/organizer/competition/'. $competition->id)
              ->assertDontSeeText("Archive Competition");

    }

    public function testCompetitionPageActiveStatus()
    {

        $user = \App\User::firstWhere('username', 'test-page');
        $competition = \App\Competition::first();

        // change competition status to active
        $competition->is_archived = NULL;
        $competition->is_completed = 0;
        $competition->save();

        // check the activate competition button visibility
        $this->actingAs($user)
            ->get('/organizer/competition/'. $competition->id)
            ->assertSeeTextInOrder(["Active", "Close Competition"]);

        // check the Activate competition button visibility
        $this->actingAs($user)
            ->get('/organizer/competition/'. $competition->id)
            ->assertDontSeeText("Activate Competition");

        // check the Archive  competition button visibility
        $this->actingAs($user)
            ->get('/organizer/competition/'. $competition->id)
            ->assertDontSeeText("Archive Competition");
    }

    public function testCompetitionPageCompleteStatus()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $competition = \App\Competition::first();

        // change competition status to completed
        $competition->is_completed = 1;
        $competition->save();

        // TODO: Check to see why this often fails
        // check the activate and archive competition button visibility
        // $this->actingAs($user)
        //     ->get('/organizer/competition/'. $competition->id)
        //     ->assertSeeTextInOrder(["Completed", "Activate Competition", "Archive Competition"]);

        // check the Activate competition button visibility
        $this->actingAs($user)
            ->get('/organizer/competition/'. $competition->id)
            ->assertDontSeeText("Close Competition");
    }

    public function testCompetitionPage()
    {
        $user = \App\User::firstWhere('username', 'test-page');
        $this->actingAs($user)
            ->get('/organizer/competition/'. \App\Competition::first()->id)
            ->assertSuccessful();
    }
}
