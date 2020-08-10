<?php

// Public results
Breadcrumbs::register('results.index', function($breadcrumbs)
{
    $breadcrumbs->push('Results', route('results.index'));
});

Breadcrumbs::register('results.year', function($breadcrumbs, $year)
{
    $breadcrumbs->parent('results.index');
    $breadcrumbs->push($year, route('results.year', $year));
});

Breadcrumbs::register('results.competition.show-public', function($breadcrumbs, $competition)
{
    $breadcrumbs->parent('results.index');
    $breadcrumbs->push($competition->name, route('results.competition.show-public', $competition));
});

Breadcrumbs::register('results.division.show-public', function($breadcrumbs, $division)
{
    $breadcrumbs->parent('results.competition.show-public', $division->competition);
    $breadcrumbs->push($division->name, route('results.competition.show-public', $division));
});

Breadcrumbs::register('results.division.show', function($breadcrumbs, $division)
{
    $breadcrumbs->parent('results.competition.show-public', $division->competition);
    $breadcrumbs->push($division->name . ' - Full Results', route('results.division.show', $division, $division->access_code));
});


// BEGIN ADMIN Breadcrumbs
Breadcrumbs::register('admin.dashboard', function($breadcrumbs)
{
    $breadcrumbs->push('Dashboard', route('admin.dashboard'));
});

// Admin > Organizations
Breadcrumbs::register('admin.organization.index', function($breadcrumbs)
{
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Organizations', route('admin.organization.index'));
});

// Admin > Organizations > {Organization}
Breadcrumbs::register('admin.organization.show', function($breadcrumbs, $organization)
{
    $breadcrumbs->parent('admin.organization.index');
    $breadcrumbs->push($organization->name, route('admin.organization.show',$organization));
});

// Admin > Organizations > {Organization} > Edit
Breadcrumbs::register('admin.organization.edit', function($breadcrumbs, $organization)
{
    $breadcrumbs->parent('admin.organization.show',$organization);
    $breadcrumbs->push('Edit', route('admin.organization.edit',$organization));
});


// Admin > Choirs
Breadcrumbs::register('admin.choir.index', function($breadcrumbs)
{
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Choirs', route('admin.choir.index'));
});

// Admin > Choirs > {Choir}
Breadcrumbs::register('admin.choir.show', function($breadcrumbs, $choir)
{
    $breadcrumbs->parent('admin.choir.index');
    $breadcrumbs->push($choir->name, route('admin.choir.show',$choir));
});


// Admin > Choirs > {Choir} > Edit
Breadcrumbs::register('admin.choir.edit', function($breadcrumbs, $choir)
{
    $breadcrumbs->parent('admin.choir.show',$choir);
    $breadcrumbs->push('Edit', route('admin.choir.edit',$choir));
});


// Admin > Schools
Breadcrumbs::register('admin.school.index', function($breadcrumbs)
{
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Schools', route('admin.school.index'));
});

// Admin > Schools > {School}}
Breadcrumbs::register('admin.school.show', function($breadcrumbs, $school)
{
    $breadcrumbs->parent('admin.school.index');
    $breadcrumbs->push($school->name, route('admin.school.show',$school));
});


// Admin > Schools > {School} > Edit
Breadcrumbs::register('admin.school.edit', function($breadcrumbs, $school)
{
    $breadcrumbs->parent('admin.school.show',$school);
    $breadcrumbs->push('Edit', route('admin.school.edit',$school));
});


// Admin > Competitions
Breadcrumbs::register('admin.competition.index', function($breadcrumbs)
{
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Competitions', route('admin.competition.index'));
});

// Admin > Competitions > {Competition}
Breadcrumbs::register('admin.competition.show', function($breadcrumbs, $competition)
{
    $breadcrumbs->parent('admin.competition.index');
    $breadcrumbs->push($competition->name, route('admin.competition.show',$competition));
});


// Admin > Competitions > {Competition} > Edit
Breadcrumbs::register('admin.competition.edit', function($breadcrumbs, $competition)
{
    $breadcrumbs->parent('admin.competition.show',$competition);
    $breadcrumbs->push('Edit', route('admin.competition.edit',$competition));
});


// Admin > Judges
Breadcrumbs::register('admin.judge.index', function($breadcrumbs)
{
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('Judges', route('admin.judge.index'));
});

// Admin > Judges > {Judge}
Breadcrumbs::register('admin.judge.show', function($breadcrumbs, $judge)
{
    $breadcrumbs->parent('admin.judge.index');
    $breadcrumbs->push($judge->full_name, route('admin.judge.show',$judge));
});


// Admin > Judges > {Judge} > Edit
Breadcrumbs::register('admin.judge.edit', function($breadcrumbs, $judge)
{
    $breadcrumbs->parent('admin.judge.show',$judge);
    $breadcrumbs->push('Edit', route('admin.judge.edit',$judge));
});



// END ADMIN Breadcrumbs




// BEGIN ORGANIZATION Breadcrumbs
Breadcrumbs::register('organizer.dashboard', function($breadcrumbs)
{
    $breadcrumbs->push('Dashboard', route('organizer.dashboard'));
});

Breadcrumbs::register('organizer.organization.show', function($breadcrumbs)
{
    $breadcrumbs->parent('organizer.dashboard');
    $breadcrumbs->push('My Organization', route('organizer.organization.show'));
});


Breadcrumbs::register('organizer.organization.edit', function($breadcrumbs)
{
    $breadcrumbs->parent('organizer.organization.show');
    $breadcrumbs->push('Edit', route('organizer.organization.edit'));
});



// Users
Breadcrumbs::register('organizer.user.index', function($breadcrumbs)
{
    $breadcrumbs->parent('organizer.dashboard');
    $breadcrumbs->push('Users', route('organizer.user.index'));
});

// Users > Create New User
Breadcrumbs::register('organizer.user.create', function($breadcrumbs)
{
		$breadcrumbs->parent('organizer.user.index');
    $breadcrumbs->push('Create New User', route('organizer.user.create'));
});

// Users > [User]
Breadcrumbs::register('organizer.user.show', function($breadcrumbs, $user)
{
		$breadcrumbs->parent('organizer.user.index');
    $breadcrumbs->push($user->email, route('organizer.user.show',[$user]));
});

// Users > [User] > Edit
Breadcrumbs::register('organizer.user.edit', function($breadcrumbs, $user)
{
		$breadcrumbs->parent('organizer.user.show', $user);
    $breadcrumbs->push('Edit', route('organizer.user.edit',[$user]));
});


// Competitions
Breadcrumbs::register('organizer.competition.index', function($breadcrumbs)
{
    $breadcrumbs->push('Competitions', route('organizer.competition.index'));
});

// Competitions > [Competition]
Breadcrumbs::register('organizer.competition.show', function($breadcrumbs, $competition)
{
    $breadcrumbs->parent('organizer.competition.index');
    $breadcrumbs->push($competition->name, route('organizer.competition.show',$competition));
});

// Organizer > Competitions > {Competition} > Edit
Breadcrumbs::register('organizer.competition.edit', function($breadcrumbs, $competition)
{
    $breadcrumbs->parent('organizer.competition.show',$competition);
    $breadcrumbs->push('Edit', route('organizer.competition.edit',$competition));
});

// Organizer > Competitions > {Competition} > Clone
Breadcrumbs::register('organizer.competition.clone', function($breadcrumbs, $competition)
{
    $breadcrumbs->parent('organizer.competition.show',$competition);
    $breadcrumbs->push('Clone', route('organizer.competition.clone',$competition));
});

// Competitions > [Competition] > Schedules
Breadcrumbs::register('organizer.competition.schedule.index', function($breadcrumbs, $competition)
{
    $breadcrumbs->parent('organizer.competition.show',$competition);
    $breadcrumbs->push('Schedules', route('organizer.competition.schedule.index',$competition));
});

// Competitions > [Competition] > Schedules > [Schedule]
Breadcrumbs::register('organizer.competition.schedule.show', function($breadcrumbs, $competition, $schedule)
{
    $breadcrumbs->parent('organizer.competition.schedule.index',$competition);
    $breadcrumbs->push($schedule->name, route('organizer.competition.schedule.show', [$competition, $schedule]));
});

// Competitions > [Competition] > Schedules > [Schedule] > Edit
Breadcrumbs::register('organizer.competition.schedule.edit', function($breadcrumbs, $competition, $schedule)
{
    $breadcrumbs->parent('organizer.competition.schedule.show', $competition, $schedule);
    $breadcrumbs->push($schedule->name, route('organizer.competition.schedule.edit', [$competition, $schedule]));
});

///

// Competitions > [Competition] > Award Schedules
Breadcrumbs::register('organizer.competition.award-schedule.index', function($breadcrumbs, $competition)
{
    $breadcrumbs->parent('organizer.competition.show',$competition);
    $breadcrumbs->push('Schedules', route('organizer.competition.award-schedule.index',$competition));
});

// Competitions > [Competition] > Award Schedules > [Schedule]
Breadcrumbs::register('organizer.competition.award-schedule.show', function($breadcrumbs, $competition, $schedule)
{
    $breadcrumbs->parent('organizer.competition.award-schedule.index',$competition);
    $breadcrumbs->push($schedule->name, route('organizer.competition.award-schedule.show', [$competition, $schedule]));
});

// Competitions > [Competition] > Award Schedules > [Schedule] > Edit
Breadcrumbs::register('organizer.competition.award-schedule.edit', function($breadcrumbs, $competition, $schedule)
{
    $breadcrumbs->parent('organizer.competition.award-schedule.show', $competition, $schedule);
    $breadcrumbs->push($schedule->name, route('organizer.competition.award-schedule.edit', [$competition, $schedule]));
});

///

// Competitions > [Competition] > Divisions
Breadcrumbs::register('organizer.competition.division.index', function($breadcrumbs, $competition)
{
    $breadcrumbs->parent('organizer.competition.show',$competition);
    $breadcrumbs->push('Divisions', route('organizer.competition.division.index',$competition));
});

// Competitions > [Competition] > Divisions > Create Division
Breadcrumbs::register('organizer.competition.division.create', function($breadcrumbs, $competition)
{
    $breadcrumbs->parent('organizer.competition.show',$competition);
    $breadcrumbs->push('Create a division', route('organizer.competition.division.create',[$competition]));
});

// Competitions > [Competition] > Divisions > [Division]
Breadcrumbs::register('organizer.competition.division.show', function($breadcrumbs, $competition, $division)
{
    $breadcrumbs->parent('organizer.competition.show',$competition);
    $breadcrumbs->push($division->name, route('organizer.competition.division.show',[$competition,$division]));
});

// Competitions > [Competition] > Divisions > [Division] > Edit
Breadcrumbs::register('organizer.competition.division.edit', function($breadcrumbs, $competition, $division)
{
    $breadcrumbs->parent('organizer.competition.division.show',$competition, $division);
    $breadcrumbs->push('Edit', route('organizer.competition.division.edit',[$competition,$division]));
});

// Competitions > [Competition] > Divisions > [Division] > Clone
Breadcrumbs::register('organizer.competition.division.clone', function($breadcrumbs, $competition, $division)
{
    $breadcrumbs->parent('organizer.competition.division.show',$competition, $division);
    $breadcrumbs->push('Clone', route('organizer.competition.division.clone',[$competition,$division]));
});

// Competitions > [Competition] > Divisions > [Division] > Choirs
Breadcrumbs::register('organizer.competition.division.choir.index', function($breadcrumbs, $competition, $division)
{
    $breadcrumbs->parent('organizer.competition.division.show',$competition, $division);
    $breadcrumbs->push('Choirs', route('organizer.competition.division.choir.index',[$competition,$division]));
});

// Competitions > [Competition] > Divisions > [Division] > Choirs > [Choir]
Breadcrumbs::register('organizer.competition.division.choir.show', function($breadcrumbs, $competition, $division, $choir)
{
    $breadcrumbs->parent('organizer.competition.division.choir.index',$competition, $division);
    $breadcrumbs->push($choir->name, route('organizer.competition.division.choir.show',[$competition,$division,$choir]));
});



// Competitions > [Competition] > Divisions > [Division] > Rounds
Breadcrumbs::register('organizer.competition.division.round.index', function($breadcrumbs, $competition, $division)
{
    $breadcrumbs->parent('organizer.competition.division.show',$competition, $division);
    $breadcrumbs->push('Rounds', route('organizer.competition.division.round.index',[$competition,$division]));
});

// Competitions > [Competition] > Divisions > [Division] > Rounds > [Round]
/*Breadcrumbs::register('organizer.competition.division.round.show', function($breadcrumbs, $competition, $division, $round)
{
    $breadcrumbs->parent('organizer.competition.division.round.index',$competition, $division);
    $breadcrumbs->push($round->name, route('organizer.competition.division.round.show',[$competition,$division,$round]));
});*/


// Competitions > [Competition] > Divisions > [Division] > Judges
Breadcrumbs::register('organizer.competition.division.judge.index', function($breadcrumbs, $competition, $division)
{
    $breadcrumbs->parent('organizer.competition.division.show',$competition, $division);
    $breadcrumbs->push('Judges', route('organizer.competition.division.judge.index',[$competition,$division]));
});

// Competitions > [Competition] > Divisions > [Division] > Judges > [Judge]
Breadcrumbs::register('organizer.competition.division.judge.show', function($breadcrumbs, $competition, $division, $judge)
{
    $breadcrumbs->parent('organizer.competition.division.judge.index',$competition, $division);
    $breadcrumbs->push($judge->first_name, route('organizer.competition.division.judge.show',[$competition,$division,$judge]));
});


// Competitions > [Competition] > Divisions > [Division] > [Round]
Breadcrumbs::register('organizer.competition.division.round.show', function($breadcrumbs, $competition, $division, $round)
{
    $breadcrumbs->parent('organizer.competition.division.show',$competition, $division);
    $breadcrumbs->push($round->name, route('organizer.competition.division.round.show',[$competition,$division,$round]));
});


// Competitions > [Competition] > Divisions > [Division] > [Round] > [Choir]
Breadcrumbs::register('organizer.competition.division.round.choir.show', function($breadcrumbs, $competition, $division, $round, $choir)
{
    $breadcrumbs->parent('organizer.competition.division.round.show',$competition, $division, $round);
    $breadcrumbs->push($choir->name, route('organizer.competition.division.round.choir.show',[$competition,$division,$round, $choir]));
});


// Competitions > [Competition] > Divisions > [Division] > [Round] > [Judge]
Breadcrumbs::register('organizer.competition.division.round.judge.show', function($breadcrumbs, $competition, $division, $round, $judge)
{
    $breadcrumbs->parent('organizer.competition.division.round.show',$competition, $division, $round);
    $breadcrumbs->push($judge->full_name, route('organizer.competition.division.round.judge.show',[$competition,$division,$round, $judge]));
});


// Competitions > [Competition] > Divisions > [Division] > [Round] > [Choir] > [Judge]
Breadcrumbs::register('organizer.competition.division.round.choir.judge.show', function($breadcrumbs, $competition, $division, $round, $choir, $judge)
{
    $breadcrumbs->parent('organizer.competition.division.round.choir.show',$competition, $division, $round, $choir);
    $breadcrumbs->push($judge->full_name, route('organizer.competition.division.round.choir.judge.show',[$competition,$division,$round, $choir, $judge]));
});


// Competitions > [Competition] > Divisions > [Division] > Round > [Round] > [Choir] > [Judge]
Breadcrumbs::register('organizer.competition.division.round.choir.judge.index', function($breadcrumbs, $competition, $division, $round, $choir)
{
    $breadcrumbs->parent('organizer.competition.division.round.choir.show',$competition, $division, $round, $choir);
    $breadcrumbs->push('Judges', route('organizer.competition.division.round.choir.judge.index',[$competition,$division,$round, $choir]));
});


// Competitions > [Competition] > Divisions > [Division] > Round > [Round] > Judges
Breadcrumbs::register('organizer.competition.division.round.judge.index', function($breadcrumbs, $competition, $division, $round)
{
    $breadcrumbs->parent('organizer.competition.division.round.show',$competition, $division, $round);
    $breadcrumbs->push('Judges', route('organizer.competition.division.round.judge.index',[$competition,$division,$round]));
});


// Judge Pages

// Competitions > [Competition]
Breadcrumbs::register('judge.competition.show', function($breadcrumbs, $competition)
{
    //$breadcrumbs->parent('organizer.competition.division.show',$competition, $division);
    $breadcrumbs->push($competition->name, route('competition.show',[$competition]));
});

// Competitions > [Competition] > Divisions > [Division]
Breadcrumbs::register('judge.competition.division.show', function($breadcrumbs, $competition, $division)
{
    $breadcrumbs->parent('judge.competition.show',$competition);
    $breadcrumbs->push($division->name, route('competition.division.show',[$competition,$division]));
});



// Judge - Scoring Pages
//
//
//
Breadcrumbs::register('round.show', function($breadcrumbs, $competition, $division, $round)
{
    $breadcrumbs->parent('competition.division.show',$competition, $division);
    $breadcrumbs->push($round->name, route('round.show',[$competition,$division,$round]));
});

Breadcrumbs::register('round.scores', function($breadcrumbs, $competition, $division, $round)
{
    $breadcrumbs->parent('round.show',$competition, $division,$round);
    $breadcrumbs->push('Scores', route('round.scores',[$competition,$division,$round]));
});

Breadcrumbs::register('round.scores.ranked', function($breadcrumbs, $competition, $division, $round)
{
    $breadcrumbs->parent('round.scores',$competition, $division,$round);
    $breadcrumbs->push('Rankings', route('round.scores',[$competition,$division,$round]));
});

Breadcrumbs::register('round.scores.mine', function($breadcrumbs, $competition, $division, $round)
{
    $breadcrumbs->parent('round.scores',$competition, $division,$round);
    $breadcrumbs->push('My Scores', route('round.scores.mine',[$competition,$division,$round]));
});

Breadcrumbs::register('round.scores.mine.ranked', function($breadcrumbs, $competition, $division, $round)
{
    $breadcrumbs->parent('round.scores.mine',$competition, $division,$round);
    $breadcrumbs->push('Rankings', route('round.scores.mine.ranked',[$competition,$division,$round]));
});


Breadcrumbs::register('round.scores.judge.show', function($breadcrumbs, $competition, $division, $round,$judge)
{
    $breadcrumbs->parent('round.scores',$competition, $division,$round);
    $breadcrumbs->push($judge->full_name, route('round.scores.judge.show',[$competition,$division,$round,$judge]));
});

Breadcrumbs::register('round.scores.judge.show.ranked', function($breadcrumbs, $competition, $division, $round,$judge)
{
    $breadcrumbs->parent('round.scores.judge.show',$competition, $division,$round,$judge);
    $breadcrumbs->push('Rankings', route('round.scores.judge.show.ranked',[$competition,$division,$round,$judge]));
});


Breadcrumbs::register('round.scores.choir.show', function($breadcrumbs, $competition, $division, $round,$choir)
{
    $breadcrumbs->parent('round.scores',$competition, $division,$round);
    $breadcrumbs->push($choir->name, route('round.scores.choir.show',[$competition,$division,$round,$choir]));
});


Breadcrumbs::register('round.scores.choir.show.ranked', function($breadcrumbs, $competition, $division, $round,$choir)
{
    $breadcrumbs->parent('round.scores.choir.show',$competition, $division,$round,$choir);
    $breadcrumbs->push('Rankings', route('round.scores.choir.show.ranked',[$competition,$division,$round,$choir]));
});


Breadcrumbs::register('round.scores.choir.judge.show', function($breadcrumbs, $competition, $division, $round,$choir,$judge)
{
    $breadcrumbs->parent('round.scores.choir.show',$competition, $division,$round,$choir);
    $breadcrumbs->push($judge->full_name, route('round.scores.choir.judge.show',[$competition,$division,$round,$choir,$judge]));
});

Breadcrumbs::register('round.scores.choir.judge.show.ranked', function($breadcrumbs, $competition, $division, $round,$choir,$judge)
{
    $breadcrumbs->parent('round.scores.choir.judge.show',$competition, $division,$round,$choir,$judge);
    $breadcrumbs->push('Rankings', route('round.scores.choir.judge.show.ranked',[$competition,$division,$round,$choir,$judge]));
});


Breadcrumbs::register('round.scores.choir.show.mine', function($breadcrumbs, $competition, $division, $round,$choir)
{
    $breadcrumbs->parent('round.scores.choir.show',$competition, $division,$round,$choir);
    $breadcrumbs->push('My Scores', route('round.scores.choir.show.mine',[$competition,$division,$round,$choir]));
});

Breadcrumbs::register('round.scores.choir.show.mine.ranked', function($breadcrumbs, $competition, $division, $round,$choir)
{
    $breadcrumbs->parent('round.scores.choir.show.mine',$competition, $division,$round,$choir);
    $breadcrumbs->push('Rankings', route('round.scores.choir.show.mine.ranked',[$competition,$division,$round,$choir]));
});






//
//
// End Judge - Scoring Pages
