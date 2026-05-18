<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Prepare data for ApexCharts
        // 1. Scores by Category
        $scoresByCategory = Score::where('user_id', $user->id)
            ->join('games', 'scores.game_id', '=', 'games.id')
            ->selectRaw('games.category, sum(scores.score) as total_score')
            ->groupBy('games.category')
            ->get();

        $pieLabels = $scoresByCategory->pluck('category');
        $pieData = $scoresByCategory->pluck('total_score');

        // 2. XP Growth over last 7 days
        $recentScores = Score::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at')
            ->get()
            ->groupBy(function($val) {
                return \Carbon\Carbon::parse($val->created_at)->format('M d');
            });

        $lineLabels = [];
        $lineData = [];
        foreach($recentScores as $date => $scores) {
            $lineLabels[] = $date;
            $lineData[] = $scores->sum('xp_earned');
        }

        return view('analytics.index', compact('pieLabels', 'pieData', 'lineLabels', 'lineData'));
    }
}
