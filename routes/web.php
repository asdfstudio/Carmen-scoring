<?php

use App\Http\Controllers\Organizer\CompetitionController;

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
  'as' => 'feedback.show',
  'uses' => 'FeedbackController@show'
]);

Route::get('results/division/{division}/standings/{access_code}', [
  'as' => 'results.division.standings',
  'uses' => 'ResultsController@divisionStandings'
]);

Route::get('results/division/{division}/scores/{access_code}', [
  'as' => 'results.division.scores',
  'uses' => 'ResultsController@divisionScores'
]);

Route::get('results/division/{division}/round/{round}/scores/{access_code}', [
  'as' => 'results.round.scores',
  'uses' => 'ResultsController@roundScores'
]);

Route::get('results/round/{round}/audience-vote-results/', [
  'as' => 'results.round.audience-vote-results',
  'uses' => 'ResultsController@audienceVoteResult'
]);

Route::get('results/division/{division}/round/{round}/choir/{choir}/{access_code}', [
  'as' => 'results.division.round.choir.show',
  'uses' => 'ResultsController@divisionRoundChoir'
]);

Route::get('results/division/{division}/round/{round}/judge/{judge}/{access_code}', [
  'as' => 'results.division.round.judge.show',
  'uses' => 'ResultsController@divisionRoundJudge'
]);

Route::get('results/division/{division}/{access_code}', [
  'as' => 'results.division.show',
  'uses' => 'ResultsController@division'
]);


Route::get('results/division/{division}', [
  'as' => 'results.division.show-public',
  'uses' => 'ResultsController@divisionPublic'
]);

Route::post('results/division/{division}', [
  'as' => 'results.division.access-protected',
  'uses' => 'ResultsController@divisionAccessProtected'
]);

Route::match(['get', 'post'], 'results/solo-division/{soloDivision}/performer/{performer}/{access_code?}/{director_email?}/', [
  'as' => 'results.solo-division.performer.show',
  'uses' => 'ResultsController@soloDivisionPerformer'
]);

Route::match(['get', 'post'], 'results/solo-division/{soloDivision}/{access_code?}/', [
  'as' => 'results.solo-division.show',
  'uses' => 'ResultsController@soloDivision'
]);

Route::get('results/competition/{competition}', [
  'as' => 'results.competition.show-public',
  'uses' => 'ResultsController@competitionPublic'
]);

Route::any('/results/view/{competition_slug}', [
  'as' => 'results.competition.show-custom',
  'uses' => 'ResultsController@competitionCustom'
]);

Route::get('results/{year}', [
  'as' => 'results.year',
  'uses' => 'ResultsController@indexYear'
]);

Route::get('results', [
  'as' => 'results.index',
  'uses' => 'ResultsController@index'
]);

Route::get('profile', [
  'as' => 'profile.edit',
  'uses' => 'ProfileController@edit'
]);

Route::patch('profile', [
  'as' => 'profile.update',
  'uses' => 'ProfileController@update'
]);

Route::get('profile/password', [
  'as' => 'password.edit',
  'uses' => 'PasswordController@edit'
]);

Route::put('profile/password', [
  'as' => 'password.update.self',
  'uses' => 'PasswordController@update'
]);


Auth::routes();
//Auth::routes(['verify' => true]);
Route::post('/user-login', 'Auth\LoginController@loginAjax');
Route::post('/vote-logout', 'Auth\LoginController@voteLogOut');
Route::post('/user-register', 'Auth\RegisterController@registerAjax');
Route::post('/user-forgot', 'Auth\ForgotPasswordController@sendResetLinkEmailAjax');
Route::post('/user-vote', 'VoteController@vote');

Route::post('/competition/{competition}/activate-all-scoring', [CompetitionController::class, 'activateAllScoring'])
    ->name('organizer.competition.activate_all_scoring');
Route::post('/competition/{competition}/complete-all-scoring', [CompetitionController::class, 'completeAllScoring'])
    ->name('organizer.competition.complete_all_scoring');

Route::post('/competition/{competition}/send-all-scores-feedback', [CompetitionController::class, 'sendAllScoresAndFeedback'])
    ->name('organizer.competition.send_all_scores_feedback');


// Audience Vote Routes
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('home/{organizer}/{alias}', [
  'as' => 'home.organizer',
  'uses' => 'HomeController@organizer'
]);

Route::get('home/{organizer}/{alias}/results', [
  'as' => 'home.organizer.results',
  'uses' => 'ResultsController@showAudienceVoteResult'
]);

Route::get('solo-division/{organizer}/{alias}', [
  'as' => 'home.solo-division',
  'uses' => 'HomeController@soloDivisionVote'
]);

Route::get('solo-division/{organizer}/{alias}/results', [
  'as' => 'home.solo-division.results',
  'uses' => 'ResultsController@viewSoloAudienceVoteResult'
]);

Route::post('buy-petl-points', [
  'as' => 'buy-petl-points',
  'uses' => 'PaymentController@paymentStripe'
]);

// Route::group(['middleware' => ['auth']], function () {
//   Route::get('organizer/competition/{competition}/recap', [App\Http\Controllers\Organizer\CompetitionController::class, 'showRecap'])
//       ->name('organizer.competition.recap.show')
//       ->middleware('can:view,competition');

//   Route::get('organizer/competition/{competition}/recap/download', [App\Http\Controllers\Organizer\CompetitionController::class, 'downloadRecap'])
//       ->name('organizer.competition.recap.download')
//       ->middleware('can:view,competition');
// });

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

Route::get('/download-audio', function (Illuminate\Http\Request $request) {
    $fileUrl = urldecode($request->query('url'));
    $filename = basename($fileUrl) . '.m4a';

    return new StreamedResponse(function () use ($fileUrl) {
        $stream = fopen($fileUrl, 'r');
        while (!feof($stream)) {
            echo fread($stream, 1024 * 8); // Stream in 8KB chunks
            ob_flush(); // Send output to the browser
            flush(); // Flush the system output buffer
        }
        fclose($stream);
    }, 200, [
        'Content-Type' => 'audio/x-m4a',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
    ]);
})->name('download.audio');



Route::group(['middleware' => ['auth']], function () {
  //   Route::get('organizer/competition/{competition}/recap', [App\Http\Controllers\Organizer\CompetitionController::class, 'showRecap'])
//       ->name('organizer.competition.recap.show')
//       ->middleware('can:view,competition');

  //   Route::get('organizer/competition/{competition}/recap/download', [App\Http\Controllers\Organizer\CompetitionController::class, 'downloadRecap'])
//       ->name('organizer.competition.recap.download')
//       ->middleware('can:view,competition');
// });
// Route::get('organizer/competition/{competition}/recap', [
//   'as' => 'organizer.competition.recap.show', 
//   'uses' => 'Organizer\CompetitionController@showRecap'
// ])->middleware('can:view,competition');

  // Route::get('organizer/competition/{competition}/recap/download', [App\Http\Controllers\Organizer\CompetitionController::class, 'downloadRecap'])
//     ->name('organizer.competition.recap.download')
//     ->middleware('can:view,competition');

  // // Show Recap Route
// Route::get('organizer/competition/{competition}/recap', [App\Http\Controllers\Organizer\CompetitionController::class, 'showRecap'])
//     ->name('organizer.competition.recap.show')
//     ->middleware('can:view,competition');

  // // Download Recap Route
// Route::get('organizer/competition/{competition}/recap/download/{format}', [App\Http\Controllers\Organizer\CompetitionController::class, 'downloadRecap'])
//     ->name('organizer.competition.recap.download')
//     ->middleware('can:view,competition');


  // Routes for authenticated users with "can:view,competition" middleware
  Route::group(['middleware' => ['auth']], function () {
    Route::get('organizer/competition/{competition}/recap', [App\Http\Controllers\Organizer\CompetitionController::class, 'showRecap'])
      ->name('organizer.competition.recap.show')
      ->middleware('can:view,competition');

    Route::get('organizer/competition/{competition}/recap/download/{format?}', [App\Http\Controllers\Organizer\CompetitionController::class, 'downloadRecap'])
      ->name('organizer.competition.recap.download')
      ->middleware('can:view,competition');
  });
});

