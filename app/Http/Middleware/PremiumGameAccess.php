<?php

namespace App\Http\Middleware;

use App\Models\Game;
use App\Models\PremiumGame;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PremiumGameAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');
        if (!$slug) {
            return $next($request);
        }

        // Find the game by slug
        $game = Game::where('slug', $slug)->first();
        if (!$game) {
            return $next($request);
        }

        // Check if there is a premium game configuration for this game
        $premiumGame = PremiumGame::where('game_id', $game->id)
            ->where('is_active', true)
            ->first();

        // If not a premium game, proceed normally
        if (!$premiumGame) {
            return $next($request);
        }

        // Check if the user has unlocked the premium game
        $user = $request->user();
        if (!$user || !$premiumGame->isUnlockedBy($user->id)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "This premium game is locked. Please unlock it in the Shop or Games dashboard first.",
                    'premium_game' => $premiumGame,
                    'locked' => true,
                ], 403);
            }

            // Display the locked view
            return response()->view('premium-games.locked', compact('premiumGame'), 403);
        }

        return $next($request);
    }
}
