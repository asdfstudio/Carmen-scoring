<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('judge-spreadsheet', function () {

  $captions = json_encode([
    [
      'id' => 1,
      'name' => 'Music',
      'color' => '#333',
      'colorDarker' => '#222',
      'colorLighter' => '#555'
    ]
  ]);

  $divisions = json_encode([
    [
      'id' => 1,
      'name' => 'Mixed High School'
    ]
  ]);

  $choirs = json_encode([
    [
      'id' => 1,
      'name' => 'Bloomington South'
    ]
  ]);

  $criteria = json_encode([
    [
      'id' => 1,
      'caption_id' => 1,
      'name' => 'Tone & Technique',
      'description' => 'This is a description of the Style criterion...',
      'minScore' => 0,
      'maxScore' => 8,
      'increment' => 0.5
    ]
  ]);

  $scores = json_encode([
    [
      'choir_id' => 1,
      'criterion_id' => 1,
      'caption_id' => 1,
      'raw_score' => 7
    ]
  ]);

  $comments = json_encode([
    [
      'choir_id' => 1,
      'comment' => 'a fake comment'
    ]
  ]);

  return view('judge.spreadsheet', compact('captions', 'divisions', 'choirs', 'criteria', 'scores', 'comments'));
});

Route::get('/', function () {
    //return view('public.home');
    return redirect('login');
});

Route::get('about', function () {
    //return view('public.about');
    return redirect('login');
});

Route::get('contact', function () {
    //return view('public.contact');
    return redirect('login');
});

Route::get('system', function () {
    //return view('public.system_information');
    return redirect('login');
});

Route::get('contest', function () {
    //return view('public.contest');
    return redirect('login');
});

/*Route::get('pdf-test', [
  'as' => 'pdf.test', 'uses' => 'ResultsPdfController@test'
]);*/

Route::get('feedback/{access_code?}', [
  'as' => 'feedback.show', 'uses' => 'FeedbackController@show'
]);

Route::get('results/division/{division}/standings/{access_code}', [
  'as' => 'results.division.standings', 'uses' => 'ResultsController@divisionStandings'
]);

Route::get('results/division/{division}/round/{round}/{access_code}', [
  'as' => 'results.division.round.show', 'uses' => 'ResultsController@divisionRound'
]);

Route::get('results/division/{division}/round-shared/{round}/{target_round_id}/{access_code}', [
  'as' => 'results.division.round-shared.show', 'uses' => 'ResultsController@divisionRoundShared'
]);

Route::get('results/division/{division}/round/{round}/choir/{choir}/{access_code}', [
  'as' => 'results.division.round.choir.show', 'uses' => 'ResultsController@divisionRoundChoir'
]);

Route::get('results/division/{division}/round/{round}/judge/{judge}/{access_code}', [
  'as' => 'results.division.round.judge.show', 'uses' => 'ResultsController@divisionRoundJudge'
]);

Route::get('results/division/{division}/{access_code}', [
  'as' => 'results.division.show', 'uses' => 'ResultsController@division'
]);

Route::get('results/division/{division}', [
  'as' => 'results.division.show-public', 'uses' => 'ResultsController@divisionPublic'
]);

Route::post('results/division/{division}', [
  'as' => 'results.division.access-protected', 'uses' => 'ResultsController@divisionAccessProtected'
]);

Route::post('results/solo-division/{soloDivision}/performer/{performer}/{access_code?}/{director_email?}/', [
  'as' => 'results.solo-division.performer.show', 'uses' => 'ResultsController@soloDivisionPerformer'
]);

Route::get('results/solo-division/{soloDivision}/performer/{performer}/{access_code?}/{director_email?}/', [
  'as' => 'results.solo-division.performer.show', 'uses' => 'ResultsController@soloDivisionPerformer'
]);

Route::post('results/solo-division/{soloDivision}/{access_code?}/', [
  'as' => 'results.solo-division.show', 'uses' => 'ResultsController@soloDivision'
]);

Route::get('results/solo-division/{soloDivision}/{access_code?}/', [
  'as' => 'results.solo-division.show', 'uses' => 'ResultsController@soloDivision'
]);



Route::get('results/competition/{competition}', [
  'as' => 'results.competition.show-public', 'uses' => 'ResultsController@competitionPublic'
]);

Route::any('/results/view/{competition_slug}', [
  'as' => 'results.competition.show-custom', 'uses' => 'ResultsController@competitionCustom'
]);

Route::get('results/{year}', [
  'as' => 'results.year', 'uses' => 'ResultsController@indexYear'
]);

Route::get('results', [
  'as' => 'results.index', 'uses' => 'ResultsController@index'
]);

Route::get('profile', [
  'as' => 'profile.edit', 'uses' => 'ProfileController@edit'
]);

Route::put('profile', [
  'as' => 'profile.update', 'uses' => 'ProfileController@update'
]);

Route::get('profile/password', [
  'as' => 'password.edit', 'uses' => 'PasswordController@edit'
]);

Route::put('profile/password', [
  'as' => 'password.update', 'uses' => 'PasswordController@update'
]);


Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

//Route::get('/home', 'HomeController@index');