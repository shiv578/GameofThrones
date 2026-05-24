<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\PremiumGame;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AllGamesController extends Controller
{
    /**
     * Display a list of all games with search and categorization.
     */
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        // Fetch regular games
        $games = Game::orderBy('name', 'asc')->get();

        // Fetch premium games
        $premiumGames = PremiumGame::with('game')
            ->where('is_active', true)
            ->get();

        // Determine which premium games are unlocked by the user
        $unlockedGameIds = $request->user()->gameUnlocks()
            ->pluck('premium_game_id')
            ->toArray();

        foreach ($premiumGames as $premium) {
            $premium->is_unlocked = in_array($premium->id, $unlockedGameIds);
        }

        return view('all-games.index', compact('games', 'premiumGames'));
    }
}
