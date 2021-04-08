<?php

use App\Http\API\Controllers\Auth\LoginController;
use App\Http\API\Controllers\Auth\RegisterController;
use App\Http\API\Controllers\Auth\ResetPasswordController;
use App\Http\API\Controllers\EventController;
use App\Http\API\Controllers\User\ProfileController;
use App\Http\API\Controllers\WebsiteController;
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

Route::get('/categories', [\App\Http\API\Controllers\EAVController::class, 'categories']);
Route::get('/categories/last-lvl', [\App\Http\API\Controllers\EAVController::class, 'lastLvlCategories']);
Route::get('/categories/{id}', [\App\Http\API\Controllers\EAVController::class, 'category']);
Route::post('/categories', [\App\Http\API\Controllers\EAVController::class, 'addCategory']);
Route::put('/categories/{id}', [\App\Http\API\Controllers\EAVController::class, 'addAttributeToCategory']);

Route::group(['namespace' => 'Auth'], function () {
    Route::post('register', [RegisterController::class, 'register'])->name('register');
    Route::get('confirmation', [RegisterController::class, 'confirm'])->name('confirmation');
    Route::get('repeat-confirmation', [RegisterController::class, 'resendConfirmationEmail'])
        ->middleware('auth')
        ->name('repeat-confirmation');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:api');
    Route::post('refresh', [LoginController::class, 'refresh'])->name('refresh-token');
    Route::post('password', [ResetPasswordController::class, 'sendResetPasswordEmail'])->name('reset-password-email');
    Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])->name('reset-password');
});

//Route::group(['middleware' => ['auth:api', 'email.verified']], function () {
    Route::post('website', [WebsiteController::class,'apply'])->name('apply_website');
    Route::post('event', [EventController::class, 'create'])->name('create_event');
    Route::post('event/save', [EventController::class, 'saveEvent'])->name('save_event');
    Route::post('links', [EventController::class, 'addLinks'])->name('add_link');
    Route::get('profile/{saved?}', [ProfileController::class, 'profile'])->name('profile');
//});

Route::get('events/{removed?}', [EventController::class, 'events'])->name('events');
Route::get('tags', [EventController::class, 'getTags'])->name('get_tags');
Route::post('view-counter', [EventController::class, 'incrementViewCount'])->name('view_counter');

Route::get('websites/{status}', [WebsiteController::class, 'websites'])->name('websites');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
