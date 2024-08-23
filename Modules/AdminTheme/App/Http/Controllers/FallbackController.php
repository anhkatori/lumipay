<?php

namespace Modules\AdminTheme\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FallbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $uri = trim($request->getRequestUri(), '/');
        if(str_starts_with($uri, 'admin')){
            return view('admintheme::404');
        }
        return redirect()->intended('/');
    }
}
