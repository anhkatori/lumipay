<?php

namespace Modules\AdminTheme\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminThemeController extends Controller
{
    public function index()
    {
        return view('admintheme::index');
    }
}
