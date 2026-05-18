<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Score;
use App\Models\GameSession;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        $categories = ['brain', 'puzzle', 'quiz', 'strategy', 'memory'];
        $games = Game::all()->groupBy('category');
        return view('games.index', compact('games', 'categories'));
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)->firstOrFail();
        $user = Auth::user();
        
        $highScore = Score::where('user_id', $user->id)
            ->where('game_id', $game->id)
            ->max('score') ?? 0;
            
        $recentScores = Score::where('user_id', $user->id)
            ->where('game_id', $game->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('games.show', compact('game', 'highScore', 'recentScores'));
    }

    public function saveScore(Request $request, $slug)
    {
        $request->validate([
            'score' => 'required|integer',
            'time_taken' => 'required|integer',
            'difficulty' => 'nullable|string'
        ]);

        $game = Game::where('slug', $slug)->firstOrFail();
        $user = Auth::user();
        
        // Calculate XP and Coins based on score
        // Formula: xp = score, coins = score / 2
        $xpEarned = $request->score;
        $coinsEarned = (int)($request->score / 2);

        // Save Score
        $score = Score::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'score' => $request->score,
            'time_taken' => $request->time_taken,
            'difficulty' => $request->difficulty,
            'xp_earned' => $xpEarned,
            'coins_earned' => $coinsEarned,
        ]);

        // Update User totals
        $user->increment('xp', $xpEarned);
        $user->increment('coins', $coinsEarned);

        // Check for achievements
        app(AchievementController::class)->checkPostGameAchievements($user, $game, $score);

        return response()->json([
            'success' => true,
            'xp_earned' => $xpEarned,
            'coins_earned' => $coinsEarned,
            'new_total_xp' => $user->xp,
            'new_total_coins' => $user->coins
        ]);
    }
}
