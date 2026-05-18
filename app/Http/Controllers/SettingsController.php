<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = Setting::firstOrCreate(['user_id' => $user->id]);
        return view('settings.index', compact('user', 'settings'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $settings = Setting::firstOrCreate(['user_id' => $user->id]);

        $request->validate([
            'volume' => 'required|integer|min:0|max:100',
            'notifications_enabled' => 'required|boolean',
            'language' => 'required|string|in:en,es,fr',
            'current_password' => 'nullable|current_password',
            'password' => 'nullable|confirmed|min:8',
        ]);

        $settings->update([
            'volume' => $request->volume,
            'notifications_enabled' => $request->notifications_enabled,
            'language' => $request->language,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return back()->with('status', 'settings-updated');
    }
}
