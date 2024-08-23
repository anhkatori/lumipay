<?php

namespace Modules\Auth\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(){
        if (Auth::check()) {
            return redirect()->route('admin.dashboard.index');
        }
        return view('auth::admin.login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended('admin');
        }

        return back()->withErrors([
            'name' => 'The name or password is incorrect.',
        ])->onlyInput('name');
    }

    /**
     * Đăng xuất người dùng
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
