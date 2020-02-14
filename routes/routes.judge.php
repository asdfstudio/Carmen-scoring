<?php

// ======================
// Begin Judge Routes
// ======================


Route::group([
  'prefix' => 'judge',
  'middleware' => ['auth','auth.judge'],
  'namespace' => 'Judge',
  'as' => 'judge.'
  ], function() {
	//Route::group(['prefix' => 'dashboard'], function () {

		Route::get('competitions', [
			'as' => 'competition.index', 'uses' => 'CompetitionController@index'
		]);

		Route::get('competition/{competition}', [
			'as' => 'competition.show', 'uses' => 'CompetitionController@show'
		]);


    Route::get('competition/{competition}/schedule', [
			'as' => 'competition.schedule.index', 'uses' => 'ScheduleController@index'
		]);

    Route::get('competition/{competition}/schedule/{schedule}', [
			'as' => 'competition.schedule.show', 'uses' => 'ScheduleController@show'
		]);

    Route::get('competition/{competition}/solo-division/{soloDivision}', [
			'as' => 'competition.solo-division.show', 'uses' => 'SoloDivisionController@show'
		]);

    Route::get('competition/{competition}/solo-division/{soloDivision}/results', [
			'as' => 'competition.solo-division.results', 'uses' => 'SoloDivisionController@results'
		]);

    Route::get('competition/{competition}/solo-division/{soloDivision}/performer/{performer}', [
			'as' => 'competition.solo-division.performer.score', 'uses' => 'SoloDivisionPerformerController@score'
		]);

    Route::post('competition/{competition}/solo-division/{soloDivision}/performer/{performer}', [
			'as' => 'competition.solo-division.performer.score.store', 'uses' => 'SoloDivisionPerformerController@storeScore'
		]);


    Route::get('competition/{competition}/solo-division/{soloDivision}/performer/{performer}/edit', [
			'as' => 'competition.solo-division.performer.edit', 'uses' => 'SoloDivisionPerformerController@edit'
		]);

    Route::post('competition/{competition}/solo-division/{soloDivision}/performer/{performer}/edit', [
			'as' => 'competition.solo-division.performer.update', 'uses' => 'SoloDivisionPerformerController@update'
		]);

    /*Route::bind('competition', function($id){
      return App\Competition::findOrFail($id);
    });*/


    Route::get('competition/{competition}/division/{division}', [
			'as' => 'competition.division.show', 'uses' => 'CompetitionDivisionController@details'
		]);

		Route::get('competition/{competition}/division/{division}/details', [
			'as' => 'competition.division.details', 'uses' => 'CompetitionDivisionController@details'
		]);


    Route::get('competition/{competition}/division/{division}/scoring', [
			'as' => 'competition.division.scoring', 'uses' => 'CompetitionDivisionController@scoring'
		]);

		/*Route::get('competition/{competition}/division/{division}/round/{round}', [
   	 'as' => 'competition.division.round.show', 'uses' => 'CompetitionDivisionRoundController@show'
		]);*/

		Route::get('competition/{competition}/division/{division}/round/{round}', [
   	 'as' => 'round.scores.summary', 'uses' => 'CompetitionDivisionRoundController@summary'
		]);

    Route::get('competition/{competition}/division/{division}/round/{round}/sources-old', [
      'as' => 'round.scores.sources-old', 'uses' => 'CompetitionDivisionRoundController@spreadsheet_sources_old'
  	]);

    Route::get('competition/{competition}/division/{division}/round/{round}/sources', [
      'as' => 'round.scores.sources', 'uses' => 'CompetitionDivisionRoundController@spreadsheet_sources'
  	]);

    Route::get('competition/{competition}/division/{division}/round/{round}/spreadsheet-old', [
   	 'as' => 'round.scores.spreadsheet-old', 'uses' => 'CompetitionDivisionRoundController@spreadsheetOld'
		]);

    Route::get('competition/{competition}/division/{division}/round/{round}/spreadsheet', [
   	 'as' => 'round.scores.spreadsheet', 'uses' => 'CompetitionDivisionRoundController@spreadsheet'
		]);

    Route::get('competition/{competition}/division/{division}/round/{round}/details', [
   	 'as' => 'round.scores.details', 'uses' => 'CompetitionDivisionRoundController@details'
		]);


		// Scoring routes
		Route::get('competition/{competition}/division/{division}/round/{round}/scores', [
   	 'as' => 'round.scores', 'uses' => 'CompetitionDivisionRoundJudgeController@index'
		]);

		Route::get('competition/{competition}/division/{division}/round/{round}/scores/ranked', [
   	 'as' => 'round.scores.ranked', 'uses' => 'CompetitionDivisionRoundJudgeController@index_ranked'
		]);

		Route::get('competition/{competition}/division/{division}/round/{round}/scores/mine', [
   	 'as' => 'round.scores.mine', 'uses' => 'CompetitionDivisionRoundController@show'
		]);

		Route::get('competition/{competition}/division/{division}/round/{round}/scores/mine/ranked', [
   	 'as' => 'round.scores.mine.ranked', 'uses' => 'CompetitionDivisionRoundController@show_ranked'
		]);

		/*Route::get('competition/{competition}/division/{division}/round/{round}/scores/mine', [
   	 'as' => 'competition.division.round.show', 'uses' => 'Judge\CompetitionDivisionRoundController@show'
		]);*/

		/*Route::get('competition/{competition}/division/{division}/round/{round}/scores/choir/{choir}', [
   	 'as' => 'round.scores.choir.show', 'uses' => 'Judge\CompetitionDivisionRoundChoirController@index'
		]);*/

		Route::get('competition/{competition}/division/{division}/round/{round}/scores/choir/{choir}/ranked', [
   	 'as' => 'round.scores.choir.show.ranked', 'uses' => 'CompetitionDivisionRoundChoirController@index_ranked'
		]);


		Route::get('competition/{competition}/division/{division}/round/{round}/scores/choir/{choir}/mine', [
   	 'as' => 'round.scores.choir.show.mine', 'uses' => 'CompetitionDivisionRoundChoirController@show'
		]);


		Route::get('competition/{competition}/division/{division}/round/{round}/scores/choir/{choir}/mine/ranked', [
   	 'as' => 'round.scores.choir.show.mine.ranked', 'uses' => 'CompetitionDivisionRoundChoirController@show_ranked'
		]);


		Route::get('competition/{competition}/division/{division}/round/{round}/scores/choir/{choir}/judge/{judge}', [
   	 'as' => 'round.scores.choir.judge.show', 'uses' => 'CompetitionDivisionRoundChoirJudgeController@show'
		]);

		Route::get('competition/{competition}/division/{division}/round/{round}/scores/choir/{choir}/judge/{judge}/ranked', [
   	 'as' => 'round.scores.choir.judge.show.ranked', 'uses' => 'CompetitionDivisionRoundChoirJudgeController@show_ranked'
		]);


		/*Route::get('competition/{competition}/division/{division}/round/{round}/choir/{choir}/judge/{judge}/ranked', [
   	 'as' => 'competition.division.round.choir.judge.show_ranked', 'uses' => 'Judge\CompetitionDivisionRoundChoirJudgeController@show_ranked'
		]);*/


		Route::get('competition/{competition}/division/{division}/round/{round}/scores/judge/{judge}/ranked', [
   	 'as' => 'round.scores.judge.show.ranked', 'uses' => 'CompetitionDivisionRoundJudgeController@show_ranked'
		]);


		Route::get('competition/{competition}/division/{division}/round/{round}/scores/judge/{judge}', [
   	 'as' => 'round.scores.judge.show', 'uses' => 'CompetitionDivisionRoundJudgeController@show'
		]);






		// End scoring routes



    Route::get('competition/{competition}/division/{division}/round/{round}/choir/{choir}', [
   	 'as' => 'competition.division.round.choir.show', 'uses' => 'CompetitionDivisionRoundChoirController@show'
		]);

    // Save score for a choir
		Route::any('competition/{competition}/division/{division}/round/{round}/choir/{choir}/save-scores', [
   	 'as' => 'competition.division.round.choir.save_scores', 'uses' => 'CompetitionDivisionRoundChoirController@save_scores'
		]);


		// Save scores for multiple choirs
		Route::any('competition/{competition}/division/{division}/round/{round}/save-scores', [
   	 'as' => 'competition.division.round.save_scores', 'uses' => 'CompetitionDivisionRoundController@save_scores'
		]);



		Route::get('score/save', [
			'as' => 'score.save', 'uses' => 'ScoreController@save'
		]);

		Route::post('score/save', [
			'as' => 'score.save', 'uses' => 'ScoreController@save'
		]);


        Route::any('comment/save', [
			'as' => 'comment.save', 'uses' => 'CommentController@save'
		]);

		Route::post('recording/save', [
			'as' => 'recording.save', 'uses' => 'RecordingController@postRecording',

		]);

		Route::get('recording', [
			'as' => 'recordings.list', 'uses' => 'RecordingController@show'
		]);

	//});
});


// ======================
// End Judge Routes
// ======================
