<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitRewards
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->user()?->id ?? $request->ip();
        $key = 'rewards_claim_' . $userId;

        // Limit to 10 claims/actions per minute per user/IP
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Too many requests. Please wait {$seconds} seconds before claiming another reward.",
                ], 429);
            }

            return back()->with('error', "Too many requests. Please wait {$seconds} seconds.");
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
