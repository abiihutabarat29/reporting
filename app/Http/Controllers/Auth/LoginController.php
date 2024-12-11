<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'         => 'required|email',
            'password'      => 'required|string|max:50',
            'math_captcha'  => 'required|max:4|in:' . $request->input('correct_sum'),
        ], [
            'email.required'         => 'Email harus diisi.',
            'email.email'            => 'Penulisan Email tidak benar.',
            'password.required'      => 'Password harus diisi.',
            'password.max'           => 'Password melebihi batas karakter.',
            'math_captcha.required'  => 'Jawaban harus diisi.',
            'math_captcha.max'       => 'Angka melebihi batas karakter.',
            'math_captcha.in'        => 'Jawaban salah.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user) {
                return redirect()->intended('dashboard');
            }

            return redirect()->intended('login');
        }

        return back()->withErrors(['email' => 'Maaf email dan password salah!'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
