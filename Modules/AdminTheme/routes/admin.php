<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminTheme\App\Http\Controllers\Admin\DashboardController;
use Modules\AdminTheme\App\Http\Controllers\Admin\AdminFallbackController;

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
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/get-chart-data', [DashboardController::class, 'getChartDataAjax'])->name('get-chart-data');

