<?php

// ======================
// Begin Organizer Routes
// ======================


Route::group([
  'prefix' => 'organizer',
  'middleware' => ['auth','auth.organizer'],
  ], function() {

  Route::get('user/{user}/password', [
    'as' => 'user.password.edit', 'uses' => 'PasswordController@edit'
  ]);

  Route::put('user/{user}/password', [
    'as' => 'user.password.update', 'uses' => 'PasswordController@update'
  ]);

});


Route::group([
  'prefix' => 'organizer',
  'middleware' => ['auth','auth.organizer'],
  'namespace' => 'Organizer'
  ], function() {


  Route::resource('user', 'UserController');

  Route::resource('penalty', 'PenaltyController');

  Route::resource('award', 'AwardController');

  // Division Awards ceremony
  Route::get('competition/{competition}/division/{division}/ceremony', [
    'as' => 'organizer.competition.division.ceremony.show', 'uses' => 'CompetitionDivisionStandingController@ceremony'
  ]);

  // Division standings
  Route::get('competition/{competition}/division/{division}/standing', [
    'as' => 'organizer.competition.division.standing.show', 'uses' => 'CompetitionDivisionStandingController@show'
  ]);

  Route::get('competition/{competition}/division/{division}/standing/{standing}/edit', [
    'as' => 'organizer.competition.division.standing.edit', 'uses' => 'CompetitionDivisionStandingController@edit'
  ]);

  Route::post('competition/{competition}/division/{division}/standing/{standing}/edit', [
    'as' => 'organizer.competition.division.standing.update', 'uses' => 'CompetitionDivisionStandingController@update'
  ]);



  // List division penalties
  Route::get('competition/{competition}/division/{division}/penalty', [
    'as' => 'organizer.competition.division.penalty.index', 'uses' => 'CompetitionDivisionPenaltyController@index'
  ]);

  // Choose round/choir to assign a penalty
  Route::get('competition/{competition}/division/{division}/penalty/assign', [
    'as' => 'organizer.competition.division.penalty.assign', 'uses' => 'CompetitionDivisionPenaltyController@assign'
  ]);

  // Create a division penalty
  Route::get('competition/{competition}/division/{division}/penalty/create', [
    'as' => 'organizer.competition.division.penalty.create', 'uses' => 'CompetitionDivisionPenaltyController@create'
  ]);

  // Save a division penalty
  Route::post('competition/{competition}/division/{division}/penalty/', [
    'as' => 'organizer.competition.division.penalty.store', 'uses' => 'CompetitionDivisionPenaltyController@store'
  ]);

  // Choose division penalties
  Route::get('competition/{competition}/division/{division}/penalty/manage', [
    'as' => 'organizer.competition.division.penalty.manage', 'uses' => 'CompetitionDivisionPenaltyController@manage'
  ]);

  // Save division penalties
  Route::post('competition/{competition}/division/{division}/penalty/manage', [
    'as' => 'organizer.competition.division.penalty.update', 'uses' => 'CompetitionDivisionPenaltyController@update'
  ]);

  // Assign penalties to choir
  Route::get('competition/{competition}/division/{division}/round/{round}/choir/{choir}/penalty', [
    'as' => 'organizer.competition.division.round.choir.penalty.assign', 'uses' => 'CompetitionDivisionRoundChoirController@assign_penalty'
	]);

  // Save assigned penalties to choir
  Route::post('competition/{competition}/division/{division}/round/{round}/choir/{choir}/penalty', [
    'as' => 'organizer.competition.division.round.choir.penalty.update_assign', 'uses' => 'CompetitionDivisionRoundChoirController@update_penalty'
	]);


  // Set choir round performance order
  Route::get('competition/{competition}/division/{division}/round/{round}/performance-order', [
    'as' => 'organizer.competition.division.round.choir.performance_order', 'uses' => 'CompetitionDivisionRoundChoirController@performance_order'
	]);

  // Save choir round performance order
  Route::post('competition/{competition}/division/{division}/round/{round}/performance-order', [
    'as' => 'organizer.competition.division.round.choir.performance_order.update', 'uses' => 'CompetitionDivisionRoundChoirController@update_performance_order'
	]);


  // List division awards
  Route::get('competition/{competition}/division/{division}/award', [
    'as' => 'organizer.competition.division.award.index', 'uses' => 'CompetitionDivisionAwardController@index'
  ]);

  // Create a division award
  Route::get('competition/{competition}/division/{division}/award/create', [
    'as' => 'organizer.competition.division.award.create', 'uses' => 'CompetitionDivisionAwardController@create'
  ]);

  // Save a division award
  Route::post('competition/{competition}/division/{division}/award/', [
    'as' => 'organizer.competition.division.award.store', 'uses' => 'CompetitionDivisionAwardController@store'
  ]);

  // Choose division awards
  Route::get('competition/{competition}/division/{division}/award/manage', [
    'as' => 'organizer.competition.division.award.manage', 'uses' => 'CompetitionDivisionAwardController@manage'
  ]);

  // Save division awards
  Route::post('competition/{competition}/division/{division}/award/manage', [
    'as' => 'organizer.competition.division.award.update', 'uses' => 'CompetitionDivisionAwardController@update'
  ]);


  // Assign division awards
  Route::get('competition/{competition}/division/{division}/award/assign', [
    'as' => 'organizer.competition.division.award.assign', 'uses' => 'CompetitionDivisionAwardController@assign'
  ]);

  // Save assigned division awards
  Route::post('competition/{competition}/division/{division}/award/assign', [
    'as' => 'organizer.competition.division.award.update_assignment', 'uses' => 'CompetitionDivisionAwardController@update_assignment'
  ]);

  // Show organization details
  Route::get('organization', [
    'as' => 'organization.show',
    'uses' => 'OrganizationController@show'
  ]);

  // Edit organization details
  Route::get('organization', [
    'as' => 'organization.edit',
    'uses' => 'OrganizationController@edit'
  ]);

  // Update organization details
  Route::post('organization', [
    'as' => 'organization.update',
    'uses' => 'OrganizationController@update'
  ]);

  Route::get('competition/{competition}/division/setup', [
    'as' => 'organizer.competition.division.setup', 'uses' => 'CompetitionDivisionController@setup'
  ]);

  Route::post('competition/{competition}/division/setup', [
    'as' => 'organizer.competition.division.setup.store', 'uses' => 'CompetitionDivisionController@storeMultiple'
  ]);

  Route::get('competition/{competition}/division/{division}/settings', [
    'as' => 'organizer.competition.division.settings', 'uses' => 'CompetitionDivisionController@settings'
  ]);

  Route::get('competition/{competition}/division/{division}/round/setup', [
    'as' => 'organizer.competition.division.round.setup', 'uses' => 'CompetitionDivisionRoundController@setup'
  ]);

  Route::post('competition/{competition}/division/{division}/round/setup', [
    'as' => 'organizer.competition.division.round.setup.store', 'uses' => 'CompetitionDivisionRoundController@storeMultiple'
  ]);

  Route::get('competition/{competition}/division/{division}/choir/setup', [
    'as' => 'organizer.competition.division.choir.setup', 'uses' => 'CompetitionDivisionChoirController@setup'
  ]);

  Route::post('competition/{competition}/division/{division}/choir/setup', [
    'as' => 'organizer.competition.division.choir.setup.store', 'uses' => 'CompetitionDivisionChoirController@storeMultiple'
  ]);


  Route::get('competition/{competition}/division/{division}/judge/import', [
    'as' => 'organizer.competition.division.judge.import', 'uses' => 'CompetitionDivisionJudgeController@import'
  ]);

  Route::post('competition/{competition}/division/{division}/judge/import', [
    'as' => 'organizer.competition.division.judge.import.process', 'uses' => 'CompetitionDivisionJudgeController@process_import'
  ]);

  Route::get('competition/{competition}/division/{division}/judge/setup', [
    'as' => 'organizer.competition.division.judge.setup', 'uses' => 'CompetitionDivisionJudgeController@setup'
  ]);

  Route::post('competition/{competition}/division/{division}/judge/setup', [
    'as' => 'organizer.competition.division.judge.setup.store', 'uses' => 'CompetitionDivisionJudgeController@storeMultiple'
  ]);

	//Route::resource('judge', 'JudgeController');
	//Route::resource('school', 'SchoolController');
	//Route::resource('choir', 'ChoirController');
	Route::resource('competition', 'CompetitionController');
	Route::resource('competition.division', 'CompetitionDivisionController');
	Route::resource('competition.division.choir', 'CompetitionDivisionChoirController');
	Route::resource('competition.division.judge', 'CompetitionDivisionJudgeController');
  Route::resource('competition.division.round', 'CompetitionDivisionRoundController');

  Route::get('dashboard', [
    'as' => 'organizer.dashboard', 'uses' => 'CompetitionController@index'
  ]);

  Route::get('competition/{competition}/division/{division}/clone', [
    'as' => 'organizer.competition.division.clone', 'uses' => 'CompetitionDivisionCloneController@clone'
  ]);

  Route::post('competition/{competition}/division/{division}/clone', [
    'as' => 'organizer.competition.division.clone.store', 'uses' => 'CompetitionDivisionCloneController@store'
  ]);

  Route::get('competition/{competition}/clone', [
    'as' => 'organizer.competition.clone', 'uses' => 'CompetitionCloneController@clone'
  ]);

  Route::post('competition/{competition}/clone', [
    'as' => 'organizer.competition.clone.store', 'uses' => 'CompetitionCloneController@store'
  ]);


  Route::post('competition/{competition}/scoring', [
    'as' => 'organizer.competition.scoring', 'uses' => 'CompetitionController@scoring'
	]);

  Route::post('competition/{competition}/division/{division}/scoring', [
    'as' => 'organizer.competition.division.scoring', 'uses' => 'CompetitionDivisionController@scoring'
	]);

  Route::post('competition/{competition}/division/{division}/round/{round}/scoring', [
    'as' => 'organizer.competition.division.round.scoring', 'uses' => 'CompetitionDivisionRoundController@scoring'
	]);


	Route::get('organization', [
    'as' => 'organizer.organization.show', 'uses' => 'OrganizationController@show'
	]);

	Route::get('organization/edit', [
    'as' => 'organizer.organization.edit', 'uses' => 'OrganizationController@edit'
	]);

	Route::patch('organization', [
    'as' => 'organizer.organization.update', 'uses' => 'OrganizationController@update'
	]);

	Route::get('competition/{competition}/division/{division}/round/{round}', [
    'as' => 'organizer.competition.division.round.show', 'uses' => 'CompetitionDivisionRoundController@show'
	]);

  Route::get('competition/{competition}/division/{division}/round/{round}/sources', [
    'as' => 'organizer.competition.division.round.show_sources', 'uses' => 'CompetitionDivisionRoundController@show_sources'
	]);


	Route::get('competition/{competition}/division/{division}/round/{round}/choir/{choir}', [
    'as' => 'organizer.competition.division.round.choir.show', 'uses' => 'CompetitionDivisionRoundChoirController@show'
	]);

	Route::get('competition/{competition}/division/{division}/round/{round}/judge/{judge}', [
    'as' => 'organizer.competition.division.round.judge.show', 'uses' => 'CompetitionDivisionRoundJudgeController@show'
	]);

	Route::get('competition/{competition}/division/{division}/round/{round}/choir/{choir}/judge/{judge}', [
    'as' => 'organizer.competition.division.round.choir.judge.show', 'uses' => 'CompetitionDivisionRoundChoirJudgeController@show'
	]);


  Route::get('competition/{competition}/division/{division}/round/{round}/scores/choir/{choir}', [
   'as' => 'organizer.round.scores.choir.show', 'uses' => 'CompetitionDivisionRoundChoirController@show'
  ]);

  Route::get('competition/{competition}/division/{division}/round/{round}/scores/judge/{judge}', [
   'as' => 'organizer.round.scores.judge.show', 'uses' => 'CompetitionDivisionRoundJudgeController@show'
  ]);

  Route::get('competition/{competition}/division/{division}/round/{round}/scores/choir/{choir}/judge/{judge}', [
   'as' => 'organizer.round.scores.choir.judge.show', 'uses' => 'CompetitionDivisionRoundChoirJudgeController@show'
  ]);

	//Route::resource('competition.division.round', 'CompetitionDivisionRoundController');
});


// ======================
// End Organizer Routes
// ======================
