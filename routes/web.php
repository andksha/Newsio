<?php

use Illuminate\Support\Facades\Route;

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

Route::get('events', "EventController@events");
Route::post('event', "EventController@create");
Route::put('event', "Admin\EventController@edit");

Route::get('test', "TestController@test");
