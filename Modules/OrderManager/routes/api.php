<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\OrderManager\App\Http\Controllers\Admin\OrderController;

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

// Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
//     Route::get('ordermanager', fn (Request $request) => $request->user())->name('ordermanager');
// });
Route::middleware(['order-api'])->prefix('v1')->name('api.')->group(function () {
    Route::get('ordermanager', [OrderController::class, 'apiCheck']);
});
Route::middleware(['order-api'])->prefix('v1')->name('api.')->group(function () {
    Route::post('order', action: [OrderController::class, 'storeOrder']);
    Route::post('order/status', action: [OrderController::class, 'checkStatusOrder']);
    Route::post('order/status/update', action: [OrderController::class, 'updateStatusOrder']);
    Route::post('order/redirect', action: [OrderController::class, 'redirectOrder']);
});
