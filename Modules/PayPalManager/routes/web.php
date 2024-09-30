<?php

use Illuminate\Support\Facades\Route;
use Modules\PayPalManager\App\Http\Controllers\PaypalAccountController;

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
    Route::resource('paypal-accounts', PaypalAccountController::class);
    Route::post('paypal-accounts/sell', 'PaypalAccountController@sell')->name('paypal-accounts.sell');
    Route::get('paypal-moneys/sold-index', 'PaypalAccountController@soldIndex')->name('paypal-moneys.sold-index');
    Route::put('paypal-moneys/sold-update/{id}', 'PaypalAccountController@updateSold')->name('paypal-moneys.sold-update');
});