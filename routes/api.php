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



Route::group(['prefix' => 'todo'], function() {

    Route::get('/status/{status}', 'Api\TodoController@getbystatus');
    Route::get('/{id}', 'Api\TodoController@show');
    Route::post('/', 'Api\TodoController@store');
    Route::put('/{id}', 'Api\TodoController@update');
    Route::delete('/{id}', 'Api\TodoController@delete');

});
