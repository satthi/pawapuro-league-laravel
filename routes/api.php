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
Route::get('/seasons/next-game/{season}', 'App\Http\Controllers\SeasonController@nextGame');
Route::get('/seasons/detail/{season}', 'App\Http\Controllers\SeasonController@detail');
Route::put('/seasons/edit/{season}', 'App\Http\Controllers\SeasonController@update');
Route::post('/seasons/re-shukei/{season}', 'App\Http\Controllers\SeasonController@reShukei');
Route::delete('/seasons/delete/{season}', 'App\Http\Controllers\SeasonController@destroy');
Route::get('/seasons/fielder-rank/{season}/{sortType}', 'App\Http\Controllers\SeasonController@fielderRank');
Route::get('/seasons/pitcher-rank/{season}/{sortType}', 'App\Http\Controllers\SeasonController@pitcherRank');

Route::get('/games/get-result', 'App\Http\Controllers\GameController@getResult');
Route::get('/games/{season}', 'App\Http\Controllers\GameController@index');
Route::get('/games/view/{game}', 'App\Http\Controllers\GameController@view');
Route::post('/games/add/{season}', 'App\Http\Controllers\GameController@add');
Route::post('/games/auto-add/{season}', 'App\Http\Controllers\GameController@autoAdd');
Route::delete('/games/delete/{game}', 'App\Http\Controllers\GameController@destroy');
Route::get('/games/get-probable-pitcher-options/{game}', 'App\Http\Controllers\GameController@getProbablePitcherOptions');
Route::put('/games/probable-pitcher-edit/{game}', 'App\Http\Controllers\GameController@probablePitcherUpdate');
Route::get('/games/get-stamen-initial-data/{game}/{stamenType}', 'App\Http\Controllers\GameController@getStamenInitialData');
Route::post('/games/stamen-edit/{game}/{stamenType}', 'App\Http\Controllers\GameController@stamenEdit');
Route::get('/games/get-stamen/{game}', 'App\Http\Controllers\GameController@getStamen');
Route::get('/games/get-play/{game}', 'App\Http\Controllers\GameController@getPlay');
Route::post('/games/save-game-start/{game}', 'App\Http\Controllers\GameController@saveGameStart');
Route::post('/games/save-play/{game}', 'App\Http\Controllers\GameController@savePlay');
Route::post('/games/save-point-only/{game}', 'App\Http\Controllers\GameController@savePointOnly');
Route::post('/games/back-play/{game}', 'App\Http\Controllers\GameController@backPlay');
Route::post('/games/back-game/{game}', 'App\Http\Controllers\GameController@backGame');
Route::post('/games/next-inning-play/{game}', 'App\Http\Controllers\GameController@nextInningPlay');
Route::post('/games/game-end-play/{game}', 'App\Http\Controllers\GameController@gameEndPlay');
Route::post('/games/save-pinch-hitter/{game}/{teamType}', 'App\Http\Controllers\GameController@savePinchHitter');
Route::post('/games/save-pinch-runner/{game}/{teamType}', 'App\Http\Controllers\GameController@savePinchRunner');
Route::post('/games/save-position-change/{game}/{teamType}', 'App\Http\Controllers\GameController@savePositionChange');
Route::post('/games/save-steal-success/{game}', 'App\Http\Controllers\GameController@saveStealSuccess');
Route::post('/games/save-steal-fail/{game}', 'App\Http\Controllers\GameController@saveStealFail');
Route::get('/games/summary/{game}', 'App\Http\Controllers\GameController@summary');
Route::get('/games/fielder-summary/{game}/{type}', 'App\Http\Controllers\GameController@fielderSummary');
Route::get('/games/pitcher-summary/{game}/{type}', 'App\Http\Controllers\GameController@pitcherSummary');


Route::get('/teams/view/{team}', 'App\Http\Controllers\TeamController@view');
Route::get('/teams/get-options/{season}', 'App\Http\Controllers\TeamController@getOptions');
Route::get('/teams/get-month-list/{team}', 'App\Http\Controllers\TeamController@getMonthList');
Route::get('/teams/get-month-info/{team}/{month}', 'App\Http\Controllers\TeamController@getMonthInfo');

Route::get('/players/view/{player}', 'App\Http\Controllers\PlayerController@view');
