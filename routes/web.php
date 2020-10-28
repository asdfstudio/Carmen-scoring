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

// Basic redirects for old pages
Route::redirect('/', '/login');
Route::redirect('/about', '/login');
Route::redirect('/contact', '/login');
Route::redirect('/system', '/login');
Route::redirect('/contact', '/login');

/*Route::get('pdf-test', [
  'as' => 'pdf.test', 'uses' => 'ResultsPdfController@test'
]);*/

Route::get('feedback/{access_code?}', [
  'as' => 'feedback.show', 'uses' => 'FeedbackController@show'
]);

Route::get('results/division/{division}/standings/{access_code}', [
  'as' => 'results.division.standings', 'uses' => 'ResultsController@divisionStandings'
]);

Route::get('results/division/{division}/audience-vote-results/{access_code}', [
  'as' => 'results.division.audience-vote-results', 'uses' => 'ResultsController@audienceVoteResult'
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

Route::match(['get', 'post'], 'results/solo-division/{soloDivision}/performer/{performer}/{access_code?}/{director_email?}/', [
  'as' => 'results.solo-division.performer.show', 'uses' => 'ResultsController@soloDivisionPerformer'
]);

Route::match(['get', 'post'], 'results/solo-division/{soloDivision}/{access_code?}/', [
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

Route::patch('profile', [
  'as' => 'profile.update', 'uses' => 'ProfileController@update'
]);

Route::get('profile/password', [
  'as' => 'password.edit', 'uses' => 'PasswordController@edit'
]);

Route::put('profile/password', [
  'as' => 'password.update.self', 'uses' => 'PasswordController@update'
]);


Auth::routes();
//Auth::routes(['verify' => true]);
Route::post('/user-login', 'Auth\LoginController@loginAjax');
Route::post('/vote-logout', 'Auth\LoginController@voteLogOut');
Route::post('/user-register', 'Auth\RegisterController@registerAjax');
Route::post('/user-forgot', 'Auth\ForgotPasswordController@sendResetLinkEmailAjax');
Route::post('/user-vote', 'VoteController@vote');

// Audience Vote Routes
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('home/{organizer}/{alias}', [
    'as' => 'home.organizer', 'uses' => 'HomeController@organizer'
]);

Route::get('home/{organizer}/{alias}/results', [
    'as' => 'home.organizer.results', 'uses' => 'ResultsController@showAudienceVoteResult'
]);

Route::get('solo-division/{organizer}/{alias}', [
  'as' => 'home.solo-division', 'uses' => 'HomeController@soloDivisionVote'
]);

Route::get('solo-division/{organizer}/{alias}/results', [
  'as' => 'home.solo-division.results', 'uses' => 'ResultsController@viewSoloAudienceVoteResult'
]);

Route::post('buy-petl-points', [
  'as' => 'buy-petl-points', 'uses' => 'PaymentController@paymentStripe'
]);
