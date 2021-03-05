<?php

use App\Http\API\Controllers\Moderator\AuthController;
use App\Http\API\Controllers\Moderator\EventController;
use Illuminate\Support\Facades\Route;

Route::post('confirmation', [AuthController::class, 'confirmModerator'])->name('confirm_moderator');

Route::post('login', [AuthController::class, 'login'])->name('moderator_login');

Route::group(['middleware' => 'auth:api_moderator'], function () {
    Route::delete('event', [EventController::class, 'removeEvent'])->name('remove_event');
    Route::put('event', [EventController::class, 'restoreEvent'])->name('restore_event');
    Route::delete('link', [EventController::class, 'removeLink'])->name('remove_link');
    Route::put('link', [EventController::class, 'restoreLink'])->name('restore_link');

    Route::get('logout', [AuthController::class, 'logout'])->name('moderator_logout');
});