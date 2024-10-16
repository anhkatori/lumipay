<?php

use Illuminate\Support\Facades\Route;
use Modules\BlockManager\App\Http\Controllers\BlockedEmailController;
use Modules\BlockManager\App\Http\Controllers\BlockedIpController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['auth'], 'as' => 'admin.', 'prefix' => 'admin'], function () {
    Route::resource('/blocked-email', BlockedEmailController::class);
    Route::resource('/blocked-ip', BlockedIpController::class);
    Route::post('blocked-email/{id}/unblock', 'BlockedEmailController@unblock')->name('blocked-email.unblock');
    Route::post('blocked-email/{id}/block', 'BlockedEmailController@block')->name('blocked-email.block');
});

