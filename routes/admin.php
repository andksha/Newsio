<?php

use Illuminate\Support\Facades\Route;

//Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('login', 'LoginController@loginForm');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout');
//});
