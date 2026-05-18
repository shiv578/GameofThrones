<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('house', 'all');

        $query = User::orderBy('xp', 'desc');

        if ($filter !== 'all') {
            $query->where('house', $filter);
        }

        $topUsers = $query->paginate(20);

        // Calculate podium (top 3)
        $podium = $topUsers->take(3);
        
        return view('leaderboards.index', compact('topUsers', 'podium', 'filter'));
    }
}
