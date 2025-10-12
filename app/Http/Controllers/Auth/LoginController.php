<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     */
     public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
            'captcha'  => 'required|captcha',
        ], [
            'captcha.required' => 'الرجاء إدخال رمز التحقق',
            'captcha.captcha'  => 'رمز التحقق غير صحيح',
        ]);

        if ($this->attemptLogin($request)) {
        return redirect()->route('home');
        }

        return $this->sendFailedLoginResponse($request);
    }
}

