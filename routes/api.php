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

Route::prefix('v1')->namespace('v1')->group(function () {
    Route::prefix('emails')->namespace('Emails')->group(function () {
        Route::post('', 'EmailsController@queue')->name('api.v1.emails.queue');
//        Route::get('','EmailController@index')->name('api.v1.emails.list');
//        Route::get("{id}", "ApiBranchesController@show");
    });
});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

