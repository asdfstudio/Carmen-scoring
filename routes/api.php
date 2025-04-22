<?php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::resource('spreadsheet', 'JudgingSpreadsheetController')->middleware(['auth', 'auth.judge']);

Route::get('/choirs/upload-totals', function () {
    return DB::table('recordings')
        ->select('choir_id', DB::raw('count(*) as total'))
        ->groupBy('choir_id')
        ->pluck('total', 'choir_id');
});