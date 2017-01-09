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

Route::get('/', function () {
    return view('public.home');
    //return redirect('login');
});

Route::get('about', function () {
    return view('public.about');
});

Route::get('contact', function () {
    return view('public.contact');
});

Route::get('system', function () {
    return view('public.system_information');
});

Route::get('contest', function () {
    return view('public.contest');
});


Route::get('results/division/{division}/standings/{access_code}', [
  'as' => 'results.division.standings', 'uses' => 'ResultsController@divisionStandings'
]);

Route::get('results/division/{division}/round/{round}/{access_code}', [
  'as' => 'results.division.round.show', 'uses' => 'ResultsController@divisionRound'
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


Route::auth();

Route::get('/home', 'HomeController@index');
