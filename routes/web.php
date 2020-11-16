<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth', 'email.verified']], function () {
    Route::post('website', 'WebsiteController@apply')->name('apply_website');
    Route::post('event', 'EventController@create')->name('create_event');
    Route::post('event/save', 'EventController@saveEvent')->name('save_event');
    Route::post('links', 'EventController@addLinks')->name('add_link');
    Route::get('profile/{saved?}', 'User\ProfileController@profile')->name('profile');
});

Route::get('events/{removed?}', 'EventController@events')->name('events');
Route::get('tags', 'EventController@getTags')->name('get_tags');
Route::post('view-counter', 'EventController@incrementViewCount')->name('view_counter');

Route::get('websites/{status}', 'WebsiteController@websites')->name('websites');

Route::get('test', 'TestController@test')->name('test');
