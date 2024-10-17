<?php

use Illuminate\Support\Facades\Route;
use Modules\ClientManager\App\Http\Controllers\Admin\ClientController;
    

Route::resource('clients', ClientController::class);
