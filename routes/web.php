<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('games.create');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::apiResource('messages', 'MessagesController');

Route::resource('games', 'GamesContorller');
Route::resource('games/{game}/fleets', 'FleetsController');
Route::resource('games/{game}/players', 'PlayersController');
Route::apiResource('positions', 'PositionsController');
Route::apiResource('turns', 'TurnsController');
