<?php

use Illuminate\Support\Facades\Route;
use Modules\OrderManager\App\Http\Controllers\Admin\OrderController;




Route::resource('ordermanager', OrderController::class);
Route::post('ordermanager/{order}/dispute', [OrderController::class, 'dispute'])->name('ordermanager.dispute');
