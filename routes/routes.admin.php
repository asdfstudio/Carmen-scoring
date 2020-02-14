<?php

// ==================
// Begin Admin Routes
// ==================

Route::group([
  'prefix' => 'admin',
  'as'=>'admin.',
  'middleware' => ['auth','auth.admin'],
  'namespace' => 'Admin'
  ], function(){

    Route::get('user/index_old', [
      'as' => 'user.index_old', 'uses' => 'UserController@index_old'
    ]);
    Route::post('user/get-new-username', [
      'as' => 'user.username.new', 'uses' => 'UserController@getNewUsername'
    ]);
    Route::post('user/{user}/judge', [
      'as' => 'user.judge.set', 'uses' => 'UserController@makeJudge'
    ]);

    //Route::singularResourceParameters();

    Route::get('organization/{user}/update-premium', [
      'as' => 'organization.premium-status', 'uses' => 'OrganizationController@updatePremiumStatus'
    ]);


  	Route::resource('organization', 'OrganizationController');
  	Route::resource('judge', 'JudgeController');
  	Route::resource('school', 'SchoolController');
  	Route::resource('choir', 'ChoirController');
    Route::resource('choir.director', 'ChoirDirectorController');
  	Route::resource('competition', 'CompetitionController');
  	Route::resource('user', 'UserController');
  	Route::resource('person', 'PersonController');

    Route::resource('sheet', 'SheetController');
    Route::resource('criteria', 'CriteriaController');
    Route::resource('caption', 'CaptionController');

    Route::get('raw-score-log', [
      'as' => 'raw-score-log.index', 'uses' => 'RawScoreLogController@index'
    ]);

    Route::get('raw-score-log/{date}', [
      'as' => 'raw-score-log.show', 'uses' => 'RawScoreLogController@show'
    ]);


    Route::get('sheet/{sheet}/manage', [
      'as' => 'sheet.manage', 'uses' => 'SheetController@manage'
    ]);

    Route::post('sheet/{sheet}/manage', [
      'as' => 'sheet.manage.update', 'uses' => 'SheetController@syncCriteria'
    ]);

    Route::get('sheet/{sheet}/manage-order', [
      'as' => 'sheet.manage-order', 'uses' => 'SheetController@manageOrder'
    ]);

    Route::post('sheet/{sheet}/manage-order', [
      'as' => 'sheet.manage-order.update', 'uses' => 'SheetController@syncCriteriaOrder'
    ]);


    Route::get('sheet/{sheet}/manage-caption-order', [
      'as' => 'sheet.manage-caption-order', 'uses' => 'SheetController@manageCaptionOrder'
    ]);

    Route::post('sheet/{sheet}/manage-caption-order', [
      'as' => 'sheet.manage-caption-order.update', 'uses' => 'SheetController@syncCaptionOrder'
    ]);


    Route::get('dashboard', [
      'as' => 'dashboard', 'uses' => 'OrganizationController@index'
    ]);

});

Route::group([
  'prefix' => 'admin',
  'middleware' => ['auth','auth.admin'],
  'namespace' => 'Admin'
  ], function(){
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


    Route::get('person/search', [
      'as' => 'person.search', 'uses' => 'PersonController@search'
    ]);
    Route::post('person/search', [
      'as' => 'person.search', 'uses' => 'PersonController@search'
    ]);


    Route::get('dedup', [
      'as' => 'dedup', 'uses' => 'DeDupController@index'
    ]);
    Route::get('dedup/convert_person_type_choir', [
      'as' => 'dedup.convert_person_type_choir', 'uses' => 'DeDupController@convert_person_type_choir'
    ]);
    Route::get('dedup/dup_list', [
      'as' => 'dedup.dup_list', 'uses' => 'DeDupController@dup_list'
    ]);
    Route::get('dedup/merge_dups', [
      'as' => 'dedup.merge_dups', 'uses' => 'DeDupController@merge_dups'
    ]);
    Route::get('dedup/merge_dups_manual', [
      'as' => 'dedup.merge_dups_manual', 'uses' => 'DeDupController@merge_dups_manual'
    ]);
    Route::post('dedup/merge_dups_manual', [
      'as' => 'dedup.merge_dups_manual', 'uses' => 'DeDupController@merge_dups_manual'
    ]);
    Route::get('dedup/delete_blanks', [
      'as' => 'dedup.delete_blanks', 'uses' => 'DeDupController@delete_blanks'
    ]);
    Route::get('dedup/dup_list_schools', [
      'as' => 'dedup.dup_list_schools', 'uses' => 'DeDupController@dup_list_schools'
    ]);
    Route::get('dedup/merge_dup_schools_manual', [
      'as' => 'dedup.merge_dup_schools_manual', 'uses' => 'DeDupController@merge_dup_schools_manual'
    ]);
    Route::post('dedup/merge_dup_schools_manual', [
      'as' => 'dedup.merge_dup_schools_manual', 'uses' => 'DeDupController@merge_dup_schools_manual'
    ]);
    Route::get('dedup/merge_dup_choirs_manual', [
      'as' => 'dedup.merge_dup_choirs_manual', 'uses' => 'DeDupController@merge_dup_choirs_manual'
    ]);
    Route::post('dedup/merge_dup_choirs_manual', [
      'as' => 'dedup.merge_dup_choirs_manual', 'uses' => 'DeDupController@merge_dup_choirs_manual'
    ]);

    Route::get('recordings', [
      'as' => 'recordings', 'uses' => 'DeDupController@recordings'
    ]);


  });
// ================
// End Admin Routes
// ================
