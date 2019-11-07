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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('repository')->group(function () {
    Route::get('test', 'GitHubRepositoryController@index');
    Route::prefix('compare')->group(function () {
        Route::get('name/{name1}/{name2}', 'GitHubRepositoryController@compareRepositoriesByNames');
        Route::get('user/{user1}/{user2}/name/{name1}/{name2}', 'GitHubRepositoryController@compareRepositoriesByUsersAndNames');
        Route::get('url', 'GitHubRepositoryController@compareRepositoriesByURLs');
    });
});


