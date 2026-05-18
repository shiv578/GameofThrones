<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'house' => ['required', 'string', 'in:Stark,Targaryen,Lannister,Baratheon'],
            'character_class' => ['required', 'string', 'in:Warrior,Sorcerer,Ranger'],
            'theme_preference' => ['nullable', 'string', 'in:fire,ice'],
        ]);

        $theme = $request->theme_preference ?? 'fire';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'house' => $request->house,
            'character_class' => $request->character_class,
            'theme_preference' => $theme,
            'xp' => 0,
            'coins' => 100,
            'level' => 1,
        ]);

        \App\Models\Setting::create([
            'user_id' => $user->id,
            'theme' => $theme,
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'registered',
            'details' => 'User created an account and joined House ' . $user->house,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
