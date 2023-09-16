<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $kredensial =  $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required' => 'Email tidak boleh kosong.',
                'email.email' => 'Penulisan email tidak benar.',
                'password.required' => 'Password tidak boleh kosong.',
            ]
        );
        if (Auth::guard('admin')->attempt($kredensial)) {
            $request->session()->regenerate();
            $redirectTo = RouteServiceProvider::HOME;
            return redirect()->intended($redirectTo);
        }
        if (Auth::guard('admkec')->attempt($kredensial)) {
            $request->session()->regenerate();
            $redirectTo = RouteServiceProvider::HOME;
            return redirect()->intended($redirectTo);
        }
        if (Auth::guard('admdesa')->attempt($kredensial)) {
            $request->session()->regenerate();
            $redirectTo = RouteServiceProvider::HOME;
            return redirect()->intended($redirectTo);
        }
        return back()->withErrors(['email' => 'Maaf email dan password salah!'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
