<?php

// ==================
// Begin Admin Routes
// ==================

Route::group([
  'prefix' => 'admin',
  'middleware' => ['auth','auth.admin'],
  'namespace' => 'Admin'
  ], function(){

    //Route::singularResourceParameters();
  	Route::resource('organization', 'OrganizationController');
  	Route::resource('judge', 'JudgeController');
  	Route::resource('school', 'SchoolController');
  	Route::resource('choir', 'ChoirController');
  	Route::resource('competition', 'CompetitionController');
  	Route::resource('user', 'UserController');

    Route::get('dashboard', [
      'as' => 'admin.dashboard', 'uses' => 'OrganizationController@index'
    ]);
});

// ================
// End Admin Routes
// ================
