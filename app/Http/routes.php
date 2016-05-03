<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'SiteController@index');

// site routes
Route::get('site/index', 'SiteController@index');
Route::get('site/change', [
    'as' => 'site.changeCountry',
    'uses' => 'SiteController@change',
]);
Route::post('site/phone', 'SiteController@phone');

// routes for Twilio API calls
Route::post('call/incoming', 'CallController@incoming');
Route::post('call/completed', 'CallController@completed');
Route::post('sms/incoming', 'SmsController@incoming');