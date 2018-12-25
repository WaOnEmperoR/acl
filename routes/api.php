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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');
Route::get('get_events/{begin}/{end}', 'EventController@eventsAhead');
Route::get('get_master_events', 'MasterEventController@getMasterEvents');
Route::get('get_number', 'MasterEventController@testNumber');
Route::get('get_payment_sessions', 'PaymentSessionController@getPaymentSessions');
Route::get('get_payment_types', 'PaymentTypeController@getPaymentTypes');
 
Route::middleware('auth:api')->group(function () {
    Route::get('user', 'PassportController@details');
    Route::get('payment/{begin}/{end}', 'PaymentController@getPaymentUser');
    Route::post('payment_submit', 'PaymentController@submitPaymentUser');
});