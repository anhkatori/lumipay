<?php

use Illuminate\Support\Facades\Route;
use Modules\PayPalManager\App\Http\Controllers\Admin\PaypalAccountController;

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


Route::resource('paypal-accounts', PaypalAccountController::class);
Route::post('paypal-accounts/sell', [PaypalAccountController::class, 'sell'])->name('paypal-accounts.sell');
Route::get('paypal-moneys/sold-index', [PaypalAccountController::class, 'soldIndex'])->name('paypal-moneys.sold-index');
Route::put('paypal-moneys/sold-update/{id}', [PaypalAccountController::class, 'updateSold'])->name('paypal-moneys.sold-update');