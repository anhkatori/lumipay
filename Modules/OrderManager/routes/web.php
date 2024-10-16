<?php

use Illuminate\Support\Facades\Route;
use Modules\OrderManager\App\Http\Controllers\OrderController;

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
    Route::resource('ordermanager', OrderController::class);
    Route::post('ordermanager/{order}/dispute', 'OrderController@dispute')->name('ordermanager.dispute');
});
