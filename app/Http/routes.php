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
    //return view('welcome');
    return redirect('login');
});



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
