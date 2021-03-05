<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest:api.admin'], function () {
    Route::post('login', 'LoginController@login');
});


Route::group(['middleware' => 'auth:api.admin'], function () {
    Route::get('moderators', 'ModeratorController@getModerators')->name('get_moderators');
    Route::post('moderator', 'ModeratorController@createModerator')->name('create_moderator');
    Route::put('website', 'WebsiteController@approve')->name('approve_website');
    Route::delete('website', 'WebsiteController@reject')->name('reject_website');
    Route::get('logout', 'LoginController@logout');
});
