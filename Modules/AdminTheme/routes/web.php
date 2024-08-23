<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminTheme\App\Http\Controllers\DashboardController;
use Modules\AdminTheme\App\Http\Controllers\FallbackController;

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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
});

Route::fallback([FallbackController::class, 'index']);
