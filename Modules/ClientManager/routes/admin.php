<?php

use Illuminate\Support\Facades\Route;
use Modules\ClientManager\App\Http\Controllers\Admin\ClientController;
    

Route::resource('clients', ClientController::class);
Route::post('clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
Route::post('clients/{client}/status', [ClientController::class, 'status'])->name('clients.status');
