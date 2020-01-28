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
Route::middleware('api.auth')->namespace('Api')->group(function () {
    Route::prefix('v1')->namespace('v1')->group(function () {
        Route::prefix('emails')->group(function () {
            Route::get('', 'EmailsController@index')->name('api.emails');
            Route::get('/{uuid}', 'EmailsController@status')->name('api.emails.status');

            Route::post('', 'EmailsController@queue')->name('api.emails.queue');
            Route::post('/now', 'EmailsController@now')->name('api.emails.now');
        });
        Route::prefix('sms')->group(function () {
            Route::get('', 'SmsController@index')->name('api.sms');
            Route::get('/{uuid}', 'SmsController@status')->name('api.sms.status');

            Route::post('', 'SmsController@queue')->name('api.sms.queue');
            Route::post('/now', 'SmsController@now')->name('api.sms.now');
        });

        Route::prefix('push')->group(function () {
            Route::get('', 'PushController@index')->name('api.push');
            Route::get('/{uuid}', 'PushController@status')->name('api.push.status');
            Route::post('', 'PushController@queue')->name('api.push.queue');
            Route::post('/now', 'PushController@now')->name('api.push.now');
            Route::post('/device', 'PushController@register')->name('api.push.register');
        });
    });
});
