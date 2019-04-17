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

Route::prefix('v1')->namespace('v1')->middleware('api.authorization')->group(function () {
    Route::prefix('emails')->group(function () {
        Route::post('', 'EmailsController@queue')->name('api.v1.emails.queue');
    });
    Route::prefix('messages')->group(function () {
        Route::post('', 'SMSController@queue')->name('api.v1.messages.queue');
    });
    Route::prefix('notifications')->group(function () {
        Route::post('', 'PushController@queue')->name('api.v1.notifications.queue');
    });
});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

