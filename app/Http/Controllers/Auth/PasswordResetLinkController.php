<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            $otp = sprintf("%06d", mt_rand(1, 999999));
            $user->otp = $otp;
            $user->otp_expires_at = now()->addMinutes(10);
            $user->save();

            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($otp));
            
            $request->session()->put('reset_email', $user->email);

            return redirect()->route('verify-reset-otp');
        }

        return back()->withErrors(['email' => trans('passwords.user')]);
    }
}
