<?php

use Illuminate\Support\Facades\Route;

//Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('login', 'LoginController@loginForm');
    Route::post('login', 'LoginController@login');
//});


Route::group(['middleware' => 'auth:admin'], function () {
    Route::put('website', 'WebsiteController@approve')->name('approve_website');
    Route::delete('website', 'WebsiteController@reject')->name('reject_website');
    Route::get('logout', 'LoginController@logout');
});
