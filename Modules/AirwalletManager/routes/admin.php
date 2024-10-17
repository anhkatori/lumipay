<?php

use Illuminate\Support\Facades\Route;
use Modules\AirwalletManager\App\Http\Controllers\Admin\AirwalletAccountController;
use Modules\AirwalletManager\App\Http\Controllers\Admin\AirwalletMoneyController;

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

Route::resource('airwallet-accounts', AirwalletAccountController::class);
Route::post('airwallet-money/sell/{airwalletAccount}', [AirwalletMoneyController::class, 'sell'])->name('airwallet-moneys.sell');
Route::get('airwallet-money', [AirwalletMoneyController::class, 'index'])->name('airwallet-money.index');
Route::put('airwallet-money/{airwalletMoney}', [AirwalletMoneyController::class, 'update'])->name('airwallet-moneys.update');