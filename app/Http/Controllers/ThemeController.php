<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class ThemeController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate(['theme' => 'required|in:fire,ice']);
        
        $user = Auth::user();
        if ($user) {
            $user->update(['theme_preference' => $request->theme]);
            
            $setting = Setting::firstOrCreate(['user_id' => $user->id]);
            $setting->update(['theme' => $request->theme]);
        }
        
        return response()->json(['status' => 'success', 'theme' => $request->theme]);
    }
}
