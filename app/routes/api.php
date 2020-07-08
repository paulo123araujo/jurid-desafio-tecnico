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

Route::middleware('cors')->namespace('api/v1')->prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('logout', 'SessionController@remove');
        Route::get('users/events', 'EventsController@index');
        Route::get('users/{user}/events', 'EventsController@show');
        Route::post('users/{user}/events', 'EventsController@store');
        Route::put('users/{user}/events/{event}', 'EventsController@update');
        Route::delete('users/{user}/events/{event}', 'EventsController@remove');
        Route::apiResource('users', 'UsersController');
    });

    Route::post('login', 'SessionController@store');
});
