<?php
use Illuminate\Support\Facades\Route;


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

//Game

Route::middleware('auth:api')->group(function () {
    Route::resource('game/mentals', MentalController::class);
    Route::get('test-game', 'MentalController@test');
    Route::get('generate-files', 'MentalController@generateFiles');
});