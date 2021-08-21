<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/tasks', 'App\Http\Controllers\TaskController@index');
// Route::post('/tasks', 'App\Http\Controllers\TaskController@store');
// Route::get('/tasks/{task}', 'App\Http\Controllers\TaskController@show');
// Route::put('/tasks/{task}', 'App\Http\Controllers\TaskController@update');
// Route::delete('/tasks/{task}', 'App\Http\Controllers\TaskController@destroy');

Route::get('/enums', 'App\Http\Controllers\EnumsController@index');

Route::get('/base-teams', 'App\Http\Controllers\BaseTeamController@index');
Route::get('/base-teams/get-options', 'App\Http\Controllers\BaseTeamController@getOptions');
Route::post('/base-teams', 'App\Http\Controllers\BaseTeamController@add');
Route::get('/base-teams/{baseTeam}', 'App\Http\Controllers\BaseTeamController@show');
Route::put('/base-teams/{baseTeam}', 'App\Http\Controllers\BaseTeamController@update');
Route::delete('/base-teams/{baseTeam}', 'App\Http\Controllers\BaseTeamController@destroy');

Route::get('/base-players/{baseTeam}', 'App\Http\Controllers\BasePlayerController@index');
Route::post('/base-players/{baseTeam}', 'App\Http\Controllers\BasePlayerController@add');
Route::get('/base-players/view/{basePlayer}', 'App\Http\Controllers\BasePlayerController@show');
Route::put('/base-players/{basePlayer}', 'App\Http\Controllers\BasePlayerController@update');
Route::delete('/base-players/{basePlayer}', 'App\Http\Controllers\BasePlayerController@destroy');

