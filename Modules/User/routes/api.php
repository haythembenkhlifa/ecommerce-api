<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Api\AuthController;
use Modules\User\Http\Controllers\Api\UserController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/


Route::prefix('v1')->group(function () {

    // Auth routes
    Route::prefix('auth')
        ->middleware('auth:api')
        ->group(function () {
            Route::post('login', [AuthController::class, 'login'])
                ->withoutMiddleware('auth:api')
                // ->middleware('throttle:5,1')
                ->name('login');
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('me', [AuthController::class, 'me'])->middleware('throttle:120,1')->name('me');
            Route::post('refresh-token', [AuthController::class, 'refreshToken'])->middleware('throttle:5,1')->name('refresh-token');
        });
    // Users Routes
    Route::apiResource('users', UserController::class)->middleware('auth:api')->names('user');
});
