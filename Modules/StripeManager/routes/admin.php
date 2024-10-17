<?php

use Illuminate\Support\Facades\Route;
use Modules\StripeManager\App\Http\Controllers\Admin\StripeAccountController;

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

Route::resource('stripe-accounts', StripeAccountController::class);
Route::post('stripe-accounts/sell', [StripeAccountController::class, 'sell'])->name('stripe-accounts.sell');
Route::get('stripe-moneys/sold-index', [StripeAccountController::class, 'soldIndex'])->name('stripe-moneys.sold-index');
Route::put('stripe-moneys/sold-update/{id}', [StripeAccountController::class, 'updateSold'])->name('stripe-moneys.sold-update');
