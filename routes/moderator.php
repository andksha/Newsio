<?php

use Illuminate\Support\Facades\Route;

//Route::group(['middleware' => 'auth:moderator'], function () {
    Route::delete('event', 'EventController@removeEvent')->name('remove_event');
    Route::put('event', 'EventController@restoreEvent')->name('restore_event');
    Route::delete('link', 'EventController@removeLink')->name('remove_link');
    Route::put('link', 'EventController@restoreLink')->name('restore_link');
//});