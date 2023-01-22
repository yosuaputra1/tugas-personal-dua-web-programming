<?php

namespace App\Http\Controllers;

use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{

    protected int $maxLoginAttempt = 1;
    protected int $delayLoginTime = 30;
    public function index()
    {
        return view('login', [
            'title' => 'Login'
        ]);
    }

    public function authenticate(Request $request)
    {
        $captcha = ['captcha' => 'required|captcha'];
        $captcha_validator = validator()->make(request()->all(), $captcha);
        if ($captcha_validator->fails()) {
            return back()->withErrors(['loginError' => 'Invalid captcha']);
        }

        $credentials = $request->validate([
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended();
        }

        // handle login throttling
        $email = $request->input('email');
        $current_attempt = $request->session()->get($email, 0);
        if ($current_attempt < $this->maxLoginAttempt) {
            $current_attempt++;
            $request->session()->put($email, $current_attempt);
        } else {
            $time = time() + $this->delayLoginTime;
            $request->session()->flash('nextAllowedLoginAttemptTime', $time);
            $request->session()->put($email, 0);
        }

        return back()->withErrors(['loginError' => 'Invalid credential']);
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha'=> captcha_img('mini')]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
