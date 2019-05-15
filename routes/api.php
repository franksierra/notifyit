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
        Route::post('/now', 'EmailsController@now')->name('api.v1.emails.now');
        Route::get('/{uuid}', 'EmailsController@status')->name('api.v1.emails.status');
    });
    Route::prefix('push')->group(function () {
        Route::post('/device', 'PushController@register')->name('api.v1.push.register');
        Route::post('', 'PushController@queue')->name('api.v1.push.queue');
        Route::post('/now', 'PushController@now')->name('api.v1.push.now');
        Route::get('/{uuid}', 'PushController@status')->name('api.v1.push.status');
    });
    Route::prefix('sms')->group(function () {
        Route::post('', 'SMSController@queue')->name('api.v1.sms.queue');
        Route::post('/now', 'SMSController@now')->name('api.v1.sms.now');
        Route::get('/{uuid}', 'SMSController@status')->name('api.v1.sms.status');
    });
});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

