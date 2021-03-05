<?php

use Illuminate\Support\Facades\Route;

Route::get('confirmation', 'AuthController@confirmModeratorForm')->name('confirm_moderator_form');
Route::post('confirmation', 'AuthController@confirmModerator')->name('confirm_moderator');

Route::get('login', 'AuthController@loginForm')->name('moderator_login_form');
Route::post('login', 'AuthController@login')->name('moderator_login');

Route::group(['middleware' => 'auth:moderator'], function () {
    Route::delete('event', 'EventController@removeEvent')->name('remove_event');
    Route::put('event', 'EventController@restoreEvent')->name('restore_event');
    Route::delete('link', 'EventController@removeLink')->name('remove_link');
    Route::put('link', 'EventController@restoreLink')->name('restore_link');

    Route::get('logout', 'AuthController@logout')->name('moderator_logout');
});