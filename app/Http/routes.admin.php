<?php

// ==================
// Begin Admin Routes
// ==================

Route::group([
  'prefix' => 'admin',
  'middleware' => ['auth','auth.admin'],
  'namespace' => 'Admin'
  ], function(){

    Route::post('user/{user}/judge', [
      'as' => 'admin.user.judge.set', 'uses' => 'UserController@makeJudge'
    ]);

    //Route::singularResourceParameters();
  	Route::resource('organization', 'OrganizationController');
  	Route::resource('judge', 'JudgeController');
  	Route::resource('school', 'SchoolController');
  	Route::resource('choir', 'ChoirController');
    Route::resource('choir.director', 'ChoirDirectorController');
  	Route::resource('competition', 'CompetitionController');
  	Route::resource('user', 'UserController');

    Route::get('dashboard', [
      'as' => 'admin.dashboard', 'uses' => 'OrganizationController@index'
    ]);

    Route::get('workshop', [
      'as' => 'workshop.index', 'uses' => 'WorkshopController@index'
    ]);
    Route::get('workshop/open', [
      'as' => 'workshop.open', 'uses' => 'WorkshopController@open'
    ]);
    Route::get('workshop/close', [
      'as' => 'workshop.close', 'uses' => 'WorkshopController@close'
    ]);
    Route::get('workshop/finalize', [
      'as' => 'workshop.finalize', 'uses' => 'WorkshopController@finalize'
    ]);
});

// ================
// End Admin Routes
// ================
