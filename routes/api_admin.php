<?php

use App\Http\API\Controllers\Admin\LoginController;
use App\Http\API\Controllers\Admin\ModeratorController;
use App\Http\API\Controllers\Admin\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest:api_admin'], function () {
    Route::post('login', [LoginController::class, 'login']);
});


Route::group(['middleware' => 'auth:api_admin'], function () {
    Route::get('moderators', [ModeratorController::class, 'getModerators'])->name('get_moderators');
    Route::post('moderator', [ModeratorController::class, 'createModerator'])->name('create_moderator');
    Route::put('website', [WebsiteController::class, 'approve'])->name('approve_website');
    Route::delete('website', [WebsiteController::class, 'reject'])->name('reject_website');
    Route::get('logout', [LoginController::class, 'logout']);
});
