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

// Route::post('/login', 'UserController@login')->name('login');

// Route::group(['middleware' => 'jwt.auth'], function () {
Route::group([], function () {
    Route::group(['prefix' => 'dispositivos'], function () {
        Route::get('/', 'MetricController@index')->name('dispositivos.index');
        Route::post('/', 'MetricController@store')->name('dispositivos.store');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/arduinos', 'UserController@getArduinos')->name('user.arduinos');
    });
});
