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
Route::post('/base-teams/add', 'App\Http\Controllers\BaseTeamController@add');
Route::get('/base-teams/view/{baseTeam}', 'App\Http\Controllers\BaseTeamController@show');
Route::put('/base-teams/edit/{baseTeam}', 'App\Http\Controllers\BaseTeamController@update');
Route::delete('/base-teams/delete/{baseTeam}', 'App\Http\Controllers\BaseTeamController@destroy');

Route::get('/base-players/{baseTeam}', 'App\Http\Controllers\BasePlayerController@index');
Route::post('/base-players/add/{baseTeam}', 'App\Http\Controllers\BasePlayerController@add');
Route::get('/base-players/view/{basePlayer}', 'App\Http\Controllers\BasePlayerController@show');
Route::put('/base-players/edit/{basePlayer}', 'App\Http\Controllers\BasePlayerController@update');
Route::delete('/base-players/delete/{basePlayer}', 'App\Http\Controllers\BasePlayerController@destroy');

Route::get('/seasons', 'App\Http\Controllers\SeasonController@index');
Route::post('/seasons/add', 'App\Http\Controllers\SeasonController@add');
Route::get('/seasons/view/{season}', 'App\Http\Controllers\SeasonController@show');
Route::get('/seasons/detail/{season}', 'App\Http\Controllers\SeasonController@detail');
Route::put('/seasons/edit/{season}', 'App\Http\Controllers\SeasonController@update');
Route::delete('/seasons/delete/{season}', 'App\Http\Controllers\SeasonController@destroy');

Route::get('/games/{season}', 'App\Http\Controllers\GameController@index');
Route::post('/games/add/{season}', 'App\Http\Controllers\GameController@add');
Route::post('/games/auto-add/{season}', 'App\Http\Controllers\GameController@autoAdd');
Route::delete('/games/delete/{game}', 'App\Http\Controllers\GameController@destroy');

Route::get('/teams/get-options/{season}', 'App\Http\Controllers\TeamController@getOptions');
