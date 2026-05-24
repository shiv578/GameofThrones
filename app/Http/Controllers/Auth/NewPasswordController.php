<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): \Illuminate\Http\RedirectResponse|View
    {
        if (!$request->session()->has('reset_otp_verified') || !$request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$request->session()->has('reset_otp_verified') || !$request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = $request->session()->get('reset_email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
            
            $request->session()->forget(['reset_email', 'reset_otp_verified']);

            return redirect()->route('login')->with('status', __('passwords.reset'));
        }

        return back()->withErrors(['email' => __('passwords.user')]);
    }
}
