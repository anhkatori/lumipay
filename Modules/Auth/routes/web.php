<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\LoginController;
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
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('login', [LoginController::class, 'postLogin'])->name('login.post');
Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');


