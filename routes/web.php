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

Route::group(['namespace' => 'Auth'], function () {
    Route::post('register', 'RegisterController@register');
    Route::get('confirmation', 'RegisterController@confirm');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout');
    Route::get('repeat-confirmation', 'RegisterController@resendConfirmationEmail')->middleware('auth');
    Route::post('password', 'ResetPasswordController@sendResetPasswordEmail');
    Route::get('password', 'ResetPasswordController@getPasswordResetForm');
    Route::post('password/reset', 'ResetPasswordController@resetPassword');
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('website', 'WebsiteController@apply')->name('apply_website');
});

Route::get('events/{removed?}', 'EventController@events')->name('events');
Route::post('event', 'EventController@create')->name('create_event');
Route::post('links', 'EventController@addLinks')->name('add_link');

Route::get('websites/{status}', 'WebsiteController@websites')->name('websites');

Route::get('test', 'TestController@test')->name('test');
