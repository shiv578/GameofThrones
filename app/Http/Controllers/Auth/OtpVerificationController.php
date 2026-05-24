<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OtpVerificationController extends Controller
{
    public function createLoginOtp(Request $request)
    {
        if (!$request->session()->has('login_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.verify-login-otp');
    }

    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $userId = $request->session()->get('login_user_id');
        $user = User::find($userId);

        if (!$user || $user->otp !== $request->otp || now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        Auth::login($user, $request->session()->get('login_remember', false));

        $request->session()->forget(['login_user_id', 'login_remember']);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function createResetOtp(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-reset-otp');
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = $request->session()->get('reset_email');
        $user = User::where('email', $email)->first();

        if (!$user || $user->otp !== $request->otp || now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        $request->session()->put('reset_otp_verified', true);

        return redirect()->route('password.reset.custom');
    }
}
