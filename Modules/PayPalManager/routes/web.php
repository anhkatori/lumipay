<?php

use Illuminate\Support\Facades\Route;
use Modules\PayPalManager\App\Http\Controllers\PayPalController;

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

Route::post('paypal/webhook', [PayPalController::class, 'webhook'])->name('paypal.webhook');
