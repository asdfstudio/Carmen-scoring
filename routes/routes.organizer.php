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

  Route::put('user/password/mass', 'PasswordController@update_mass')->name('user.password.update-mass');
  
});


Route::group([
  'prefix' => 'organizer',
  'as'=>'organizer.',
  'middleware' => ['auth','auth.organizer'],
  'namespace' => 'Organizer'
  ], function() {


  Route::resource('user', 'UserController');

  Route::post('user/get-new-username', [
    'as' => 'user.username.new', 'uses' => 'UserController@getNewUsername'
  ]);

  Route::resource('penalty', 'PenaltyController');

  Route::resource('award', 'AwardController');

  Route::get('competition/{competition}/feedback-links', [
    'as' => 'competition.comment-links.index', 'uses' => 'CompetitionFeedbackUrlController@index'
  ]);

  // List competition schedules
  Route::get('competition/{competition}/schedule', [
    'as' => 'competition.schedule.index', 'uses' => 'ScheduleController@index'
  ]);

  // Add a competition schedule
  Route::get('competition/{competition}/schedule/create', [
    'as' => 'competition.schedule.create', 'uses' => 'ScheduleController@create'
  ]);

  // Show a competition schedule
  Route::get('competition/{competition}/schedule/{schedule}', [
    'as' => 'competition.schedule.show', 'uses' => 'ScheduleController@show'
  ]);

  // Build a competition schedule
  Route::get('competition/{competition}/schedule/{schedule}/builder', [
    'as' => 'competition.schedule.builder', 'uses' => 'ScheduleController@builder'
  ]);

  // Store a build for a competition schedule
  Route::post('competition/{competition}/schedule/{schedule}/builder', [
    'as' => 'competition.schedule.builder.store', 'uses' => 'ScheduleController@builderStore'
  ]);

  // Destroy a competition schedule
  Route::delete('competition/{competition}/schedule/{schedule}', [
    'as' => 'competition.schedule.destroy', 'uses' => 'ScheduleController@destroy'
  ]);

  // Edit a competition schedule
  Route::get('competition/{competition}/schedule/{schedule}/edit', [
    'as' => 'competition.schedule.edit', 'uses' => 'ScheduleController@edit'
  ]);

  // update a competition schedule
  Route::post('competition/{competition}/schedule/{schedule}', [
    'as' => 'competition.schedule.update', 'uses' => 'ScheduleController@update'
  ]);

  // store a competition schedule
  Route::post('competition/{competition}/schedule', [
    'as' => 'competition.schedule.store', 'uses' => 'ScheduleController@store'
  ]);

  // update a competition schedule item
  Route::post('competition/{competition}/schedule/{schedule}/item/{item}', [
    'as' => 'competition.schedule.item.update', 'uses' => 'ScheduleItemController@update'
  ]);

  // store a competition schedule item
  Route::post('competition/{competition}/schedule/{schedule}/item', [
    'as' => 'competition.schedule.item.store', 'uses' => 'ScheduleItemController@store'
  ]);

  // Destroy a competition schedule item
  Route::delete('competition/{competition}/schedule/{schedule}/item/{item}', [
    'as' => 'competition.schedule.item.destroy', 'uses' => 'ScheduleItemController@destroy'
  ]);


  // Award schedule


  // List competition award schedules
  Route::get('competition/{competition}/award-schedule', [
    'as' => 'competition.award-schedule.index', 'uses' => 'AwardScheduleController@index'
  ]);

  // Add a competition award schedule
  Route::get('competition/{competition}/award-schedule/create', [
    'as' => 'competition.award-schedule.create', 'uses' => 'AwardScheduleController@create'
  ]);

  // Show a competition award schedule
  Route::get('competition/{competition}/award-schedule/{schedule}', [
    'as' => 'competition.award-schedule.show', 'uses' => 'AwardScheduleController@show'
  ]);

  // Show a competition award schedule in presenter mode
  Route::get('competition/{competition}/award-schedule/{schedule}/announcer', [
    'as' => 'competition.award-schedule.show-announcer', 'uses' => 'AwardScheduleController@showAsAnnouncer'
  ]);

  // Build a competition award schedule
  Route::get('competition/{competition}/award-schedule/{schedule}/builder', [
    'as' => 'competition.award-schedule.builder', 'uses' => 'AwardScheduleController@builder'
  ]);

  // Store a build for a competition award schedule
  Route::post('competition/{competition}/award-schedule/{schedule}/builder', [
    'as' => 'competition.award-schedule.builder.store', 'uses' => 'AwardScheduleController@builderStore'
  ]);

  // Destroy a competition award schedule
  Route::delete('competition/{competition}/award-schedule/{schedule}', [
    'as' => 'competition.award-schedule.destroy', 'uses' => 'AwardScheduleController@destroy'
  ]);

  // Edit a competition award schedule
  Route::get('competition/{competition}/award-schedule/{schedule}/edit', [
    'as' => 'competition.award-schedule.edit', 'uses' => 'AwardScheduleController@edit'
  ]);

  // update a competition award schedule
  Route::post('competition/{competition}/award-schedule/{schedule}', [
    'as' => 'competition.award-schedule.update', 'uses' => 'AwardScheduleController@update'
  ]);

  // store a competition award schedule
  Route::post('competition/{competition}/award-schedule', [
    'as' => 'competition.award-schedule.store', 'uses' => 'AwardScheduleController@store'
  ]);

  // End award schedule



  // Division Awards ceremony
  Route::get('competition/{competition}/division/{division}/ceremony', [
    'as' => 'competition.division.ceremony.show', 'uses' => 'CompetitionDivisionStandingController@ceremony'
  ]);

  // Division standings
  Route::get('competition/{competition}/division/{division}/standing', [
    'as' => 'competition.division.standing.show', 'uses' => 'CompetitionDivisionStandingController@show'
  ]);

  Route::get('competition/{competition}/division/{division}/standing/{standing}/edit', [
    'as' => 'competition.division.standing.edit', 'uses' => 'CompetitionDivisionStandingController@edit'
  ]);

  Route::post('competition/{competition}/division/{division}/standing/{standing}/edit', [
    'as' => 'competition.division.standing.update', 'uses' => 'CompetitionDivisionStandingController@update'
  ]);



  // List division penalties
  Route::get('competition/{competition}/division/{division}/penalty', [
    'as' => 'competition.division.penalty.index', 'uses' => 'CompetitionDivisionPenaltyController@index'
  ]);

  // Choose round/choir to assign a penalty
  Route::get('competition/{competition}/division/{division}/penalty/assign', [
    'as' => 'competition.division.penalty.assign', 'uses' => 'CompetitionDivisionPenaltyController@assign'
  ]);

  // Create a division penalty
  Route::get('competition/{competition}/division/{division}/penalty/create', [
    'as' => 'competition.division.penalty.create', 'uses' => 'CompetitionDivisionPenaltyController@create'
  ]);

  // Save a division penalty
  Route::post('competition/{competition}/division/{division}/penalty/', [
    'as' => 'competition.division.penalty.store', 'uses' => 'CompetitionDivisionPenaltyController@store'
  ]);

  // Choose division penalties
  Route::get('competition/{competition}/division/{division}/penalty/manage', [
    'as' => 'competition.division.penalty.manage', 'uses' => 'CompetitionDivisionPenaltyController@manage'
  ]);

  // Save division penalties
  Route::post('competition/{competition}/division/{division}/penalty/manage', [
    'as' => 'competition.division.penalty.update', 'uses' => 'CompetitionDivisionPenaltyController@update'
  ]);

  // Assign penalties to choir
  Route::get('competition/{competition}/division/{division}/round/{round}/choir/{choir}/penalty', [
    'as' => 'competition.division.round.choir.penalty.assign', 'uses' => 'CompetitionDivisionRoundChoirController@assign_penalty'
	]);

  // Save assigned penalties to choir
  Route::post('competition/{competition}/division/{division}/round/{round}/choir/{choir}/penalty', [
    'as' => 'competition.division.round.choir.penalty.update_assign', 'uses' => 'CompetitionDivisionRoundChoirController@update_penalty'
	]);


  // Set choir round performance order
  Route::get('competition/{competition}/division/{division}/round/{round}/performance-order', [
    'as' => 'competition.division.round.choir.performance_order', 'uses' => 'CompetitionDivisionRoundChoirController@performance_order'
	]);

  // Save choir round performance order
  Route::post('competition/{competition}/division/{division}/round/{round}/performance-order', [
    'as' => 'competition.division.round.choir.performance_order.update', 'uses' => 'CompetitionDivisionRoundChoirController@update_performance_order'
	]);


  Route::get('competition/{competition}/division/{division}/board', [
    'as' => 'competition.division.board', 'uses' => 'CompetitionDivisionController@board'
  ]);


  // List division awards
  Route::get('competition/{competition}/division/{division}/award', [
    'as' => 'competition.division.award.index', 'uses' => 'CompetitionDivisionAwardController@index'
  ]);

  // Create a division award
  Route::get('competition/{competition}/division/{division}/award/create', [
    'as' => 'competition.division.award.create', 'uses' => 'CompetitionDivisionAwardController@create'
  ]);

  // Save a division award
  Route::post('competition/{competition}/division/{division}/award/', [
    'as' => 'competition.division.award.store', 'uses' => 'CompetitionDivisionAwardController@store'
  ]);

  // Choose division awards
  Route::get('competition/{competition}/division/{division}/award/manage', [
    'as' => 'competition.division.award.manage', 'uses' => 'CompetitionDivisionAwardController@manage'
  ]);

  // Save division awards
  Route::post('competition/{competition}/division/{division}/award/manage', [
    'as' => 'competition.division.award.update', 'uses' => 'CompetitionDivisionAwardController@update'
  ]);


  // Assign division awards
  Route::get('competition/{competition}/division/{division}/award/assign', [
    'as' => 'competition.division.award.assign', 'uses' => 'CompetitionDivisionAwardController@assign'
  ]);

  // Save assigned division awards
  Route::post('competition/{competition}/division/{division}/award/assign', [
    'as' => 'competition.division.award.update_assignment', 'uses' => 'CompetitionDivisionAwardController@update_assignment'
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
    'as' => 'competition.division.setup', 'uses' => 'CompetitionDivisionController@setup'
  ]);

  Route::post('competition/{competition}/division/setup', [
    'as' => 'competition.division.setup.store', 'uses' => 'CompetitionDivisionController@storeMultiple'
  ]);

  Route::get('competition/{competition}/division/{division}/settings', [
    'as' => 'competition.division.settings', 'uses' => 'CompetitionDivisionController@settings'
  ]);

  Route::get('competition/{competition}/division/{division}/award-settings', [
    'as' => 'competition.division.award.settings.edit', 'uses' => 'CompetitionDivisionAwardSettingsController@edit'
  ]);

  Route::post('competition/{competition}/division/{division}/settings', [
    'as' => 'competition.division.award.settings.store', 'uses' => 'CompetitionDivisionAwardSettingsController@update'
  ]);

  Route::get('competition/{competition}/division/{division}/round/setup', [
    'as' => 'competition.division.round.setup', 'uses' => 'CompetitionDivisionRoundController@setup'
  ]);

  Route::post('competition/{competition}/division/{division}/round/setup', [
    'as' => 'competition.division.round.setup.store', 'uses' => 'CompetitionDivisionRoundController@storeMultiple'
  ]);

  Route::get('competition/{competition}/division/{division}/choir/setup', [
    'as' => 'competition.division.choir.setup', 'uses' => 'CompetitionDivisionChoirController@setup'
  ]);

  Route::post('competition/{competition}/division/{division}/choir/setup', [
    'as' => 'competition.division.choir.setup.store', 'uses' => 'CompetitionDivisionChoirController@storeMultiple'
  ]);

Route::get('competition/{competition}/division/{division}/audience', [
    'as' => 'competition.division.audience.index', 'uses' => 'CompetitionDivisionAudienceController@index'
]);

Route::post('competition/{competition}/division/{division}/audience/update', [
    'as' => 'competition.division.audience.store', 'uses' => 'CompetitionDivisionAudienceController@store'
]);

  Route::get('competition/{competition}/division/{division}/judge/import', [
    'as' => 'competition.division.judge.import', 'uses' => 'CompetitionDivisionJudgeController@import'
  ]);

  Route::post('competition/{competition}/division/{division}/judge/import', [
    'as' => 'competition.division.judge.import.process', 'uses' => 'CompetitionDivisionJudgeController@process_import'
  ]);

  Route::get('competition/{competition}/division/{division}/judge/setup', [
    'as' => 'competition.division.judge.setup', 'uses' => 'CompetitionDivisionJudgeController@setup'
  ]);

  Route::post('competition/{competition}/division/{division}/judge/setup', [
    'as' => 'competition.division.judge.setup.store', 'uses' => 'CompetitionDivisionJudgeController@storeMultiple'
  ]);

	//Route::resource('judge', 'JudgeController');
	//Route::resource('school', 'SchoolController');
	//Route::resource('choir', 'ChoirController');
	Route::resource('competition', 'CompetitionController');
	Route::resource('competition.division', 'CompetitionDivisionController');
	Route::resource('competition.division.choir', 'CompetitionDivisionChoirController');
	Route::resource('competition.division.judge', 'CompetitionDivisionJudgeController');
  Route::resource('competition.division.round', 'CompetitionDivisionRoundController');
  Route::resource('competition.solo-division', 'CompetitionSoloDivisionController');
  Route::resource('competition.solo-division', 'CompetitionSoloDivisionController');

  Route::get('dashboard', [
    'as' => 'dashboard', 'uses' => 'CompetitionController@index'
  ]);

  Route::get('competition/{competition}/solo-division/{soloDivision}/manage', [
    'as' => 'competition.solo-division.manage', 'uses' => 'CompetitionSoloDivisionController@manage'
  ]);

  Route::post('competition/{competition}/solo-division/{soloDivision}/manage', [
    'as' => 'competition.solo-division.manage.store', 'uses' => 'CompetitionSoloDivisionController@manageStore'
  ]);

  Route::post('competition/{competition}/solo-division/{soloDivision}/update-status', [
    'as' => 'competition.solo-division.update-status', 'uses' => 'CompetitionSoloDivisionController@updateStatus'
  ]);

  Route::get('competition/{competition}/solo-division/{soloDivision}/performer/{performer}', [
    'as' => 'competition.solo-division.performer.show', 'uses' => 'CompetitionSoloDivisionController@showPerformer'
  ]);

  Route::get('competition/{competition}/solo-division/{soloDivision}/results', [
    'as' => 'competition.solo-division.results', 'uses' => 'CompetitionSoloDivisionController@results'
  ]);

  /*Route::get('competition/{competition}/solo-division/{soloDivision}/results/female', [
    'as' => 'competition.solo-division.results.female', 'uses' => 'CompetitionSoloDivisionController@resultsFemale'
  ]);

  Route::get('competition/{competition}/solo-division/{soloDivision}/results/male', [
    'as' => 'competition.solo-division.results.male', 'uses' => 'CompetitionSoloDivisionController@resultsMale'
  ]);*/

  Route::get('competition/{competition}/division/{division}/clone', [
    'as' => 'competition.division.clone', 'uses' => 'CompetitionDivisionCloneController@clone'
  ]);

  Route::post('competition/{competition}/division/{division}/clone', [
    'as' => 'competition.division.clone.store', 'uses' => 'CompetitionDivisionCloneController@store'
  ]);

  Route::get('competition/{competition}/clone', [
    'as' => 'competition.clone', 'uses' => 'CompetitionCloneController@clone'
  ]);

  Route::post('competition/{competition}/clone', [
    'as' => 'competition.clone.store', 'uses' => 'CompetitionCloneController@store'
  ]);


  Route::post('competition/{competition}/scoring', [
    'as' => 'competition.scoring', 'uses' => 'CompetitionController@scoring'
	]);

  Route::post('competition/{competition}/division/{division}/scoring', [
    'as' => 'competition.division.scoring', 'uses' => 'CompetitionDivisionController@scoring'
	]);

  Route::post('competition/{competition}/division/{division}/round/{round}/scoring', [
    'as' => 'competition.division.round.scoring', 'uses' => 'CompetitionDivisionRoundController@scoring'
	]);


	Route::get('organization', [
    'as' => 'organization.show', 'uses' => 'OrganizationController@show'
	]);

	Route::get('organization/edit', [
    'as' => 'organization.edit', 'uses' => 'OrganizationController@edit'
	]);

	Route::patch('organization', [
    'as' => 'organization.update', 'uses' => 'OrganizationController@update'
	]);

	Route::get('competition/{competition}/division/{division}/round/{round}', [
    'as' => 'competition.division.round.show', 'uses' => 'CompetitionDivisionRoundController@show'
	]);

  Route::get('competition/{competition}/division/{division}/round/{round}/sources', [
    'as' => 'competition.division.round.show_sources', 'uses' => 'CompetitionDivisionRoundController@show_sources'
	]);


	Route::get('competition/{competition}/division/{division}/round/{round}/choir/{choir}', [
    'as' => 'competition.division.round.choir.show', 'uses' => 'CompetitionDivisionRoundChoirController@show'
	]);
	Route::post('competition/{competition}/division/{division}/round/{round}/choir/{choir}', [
    'as' => 'competition.division.round.choir.recordings', 'uses' => 'CompetitionDivisionRoundChoirController@show'
	]);


	Route::get('competition/{competition}/division/{division}/round/{round}/judge/{judge}', [
    'as' => 'competition.division.round.judge.show', 'uses' => 'CompetitionDivisionRoundJudgeController@show'
	]);

	Route::get('competition/{competition}/division/{division}/round/{round}/choir/{choir}/judge/{judge}', [
    'as' => 'competition.division.round.choir.judge.show', 'uses' => 'CompetitionDivisionRoundChoirJudgeController@show'
	]);


  Route::get('competition/{competition}/division/{division}/round/{round}/scores/choir/{choir}', [
   'as' => 'round.scores.choir.show', 'uses' => 'CompetitionDivisionRoundChoirController@show'
  ]);

  Route::get('competition/{competition}/division/{division}/round/{round}/scores/judge/{judge}', [
   'as' => 'round.scores.judge.show', 'uses' => 'CompetitionDivisionRoundJudgeController@show'
  ]);

  Route::get('competition/{competition}/division/{division}/round/{round}/scores/choir/{choir}/judge/{judge}', [
   'as' => 'round.scores.choir.judge.show', 'uses' => 'CompetitionDivisionRoundChoirJudgeController@show'
  ]);

  Route::delete('recording/delete/{recording_id}', [
    'as' => 'recording.delete', 'uses' => 'RecordingController@destroy'
    ]);

  Route::get('competition/{competitionId}/solo-division/{soloDivisionId}/audience-votes', [
    'as' => 'competition.solo-division.audience-votes', 'uses' => 'CompetitionSoloDivisionController@audienceVote'
  ]);

  Route::post('competition/{competitionId}/solo-division/{soloDivisionId}/audience-votes', [
    'as' => 'competition.solo-division.audience-vote', 'uses' => 'CompetitionSoloDivisionController@soloDivisionStore'
  ]);
	//Route::resource('competition.division.round', 'CompetitionDivisionRoundController');

  Route::post('/vote-setting',[
      'as' => 'option.setting-audience', 'uses' => 'OrganizationController@voteSetting'
  ] );
});

Route::post('/audience/fileupload/','Organizer\CompetitionDivisionAudienceController@fileupload')->name('audience.fileupload');


// ======================
// End Organizer Routes
// ======================


