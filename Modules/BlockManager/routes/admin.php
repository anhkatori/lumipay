<?php

use Illuminate\Support\Facades\Route;
use Modules\BlockManager\App\Http\Controllers\Admin\BlockedEmailController;
use Modules\BlockManager\App\Http\Controllers\Admin\BlockedIpController;



Route::resource('/blocked-email', BlockedEmailController::class);
Route::resource('/blocked-ip', BlockedIpController::class);
Route::post('blocked-email/{id}/unblock', [BlockedEmailController::class, 'unblock'])->name('blocked-email.unblock');
Route::post('blocked-email/{id}/block', [BlockedEmailController::class, 'block'])->name('blocked-email.block');

