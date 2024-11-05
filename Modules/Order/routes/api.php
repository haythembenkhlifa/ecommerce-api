<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Api\OrderController;

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

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::apiResource('orders', OrderController::class)->names('order');
    Route::post('orders/batch', [OrderController::class, 'batchStore'])->name('batch-order');
});

Route::middleware('throttle:10,1')->prefix('v1')->group(function () {
    // This endpoint i create to mimic a payment gateway call
    // back to inform us if payment was successful or not for now all good just for testing. 
    Route::post(
        'payment/{order:order_number}',
        [OrderController::class, 'markOrderAsPayed']
    )->name('payment');
});
