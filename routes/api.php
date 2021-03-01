<?php

use App\Http\API\Controllers\Auth\LoginController;
use App\Http\API\Controllers\Auth\RegisterController;
use App\Http\API\Controllers\Auth\ResetPasswordController;
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


Route::group(['namespace' => 'Auth'], function () {
    Route::post('register', [RegisterController::class, 'register'])->name('register');
    Route::get('confirmation', [RegisterController::class, 'confirm'])->name('confirmation');
    Route::get('repeat-confirmation', [RegisterController::class, 'resendConfirmationEmail'])
        ->middleware('auth')
        ->name('repeat-confirmation');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('refresh', [LoginController::class, 'refresh'])->name('refresh-token');
    Route::post('password', [ResetPasswordController::class, 'sendResetPasswordEmail'])->name('reset-password-email');
    Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])->name('reset-password');
});

// TODO: move controllers and tests for routes below from newsio, check tests
Route::group(['middleware' => ['auth', 'email.verified']], function () {
    Route::post('website', 'WebsiteController@apply')->name('apply_website');
    Route::post('event', 'EventController@create')->name('create_event');
    Route::post('event/save', 'EventController@saveEvent')->name('save_event');
    Route::post('links', 'EventController@addLinks')->name('add_link');
    Route::get('profile/{saved?}', 'User\ProfileController@profile')->name('profile');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
