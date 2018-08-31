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
    return view('welcome');
});

Auth::routes();

//Route::get('/home', 'HomeController@index');
//
Route::get('/', 'PostController@index')->name('home');

Route::get('username/{id}', function ($id) {
    $user = DB::table('users')->where('id', $id)->first();
    
    return $user->name;
});

Route::get('eventtypename/{id}', function ($id) {
    $master_event = DB::table('master_events')->where('master_event_id', $id)->first();
    
    return $master_event->type_event_name;
});

Route::get('users/{id}/image', 'UserController@image');

Route::resource('users', 'UserController');

Route::resource('roles', 'RoleController');

Route::resource('permissions', 'PermissionController');

Route::resource('posts', 'PostController');

Route::resource('master_events', 'MasterEventController');

Route::resource('payment_sessions', 'PaymentSessionController');

Route::resource('payment_types', 'PaymentTypeController');

Route::get('events/getUsers', 'EventController@getUsers');

Route::resource('events', 'EventController');

Route::resource('payments', 'PaymentController');

