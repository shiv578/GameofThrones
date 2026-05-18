<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Game;
use App\Models\Score;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\ActivityLog;

 class AchievementController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allAchievements = Achievement::all();
        $unlockedIds = $user->achievements()->pluck('achievements.id')->toArray();
        
        return view('achievements.index', compact('allAchievements', 'unlockedIds'));
    }

    public function checkPostGameAchievements(User $user, Game $game, Score $score)
    {
        $achievements = Achievement::all();
        $unlockedIds = $user->achievements()->pluck('achievements.id')->toArray();

        foreach ($achievements as $ach) {
            if (in_array($ach->id, $unlockedIds)) continue;

            $unlocked = false;

            switch ($ach->requirement_type) {
                case 'games_played':
                    $count = Score::where('user_id', $user->id)->count();
                    if ($count >= $ach->requirement_value) $unlocked = true;
                    break;
                case 'score_brain':
                    if ($game->category == 'brain' && $score->score >= $ach->requirement_value) $unlocked = true;
                    break;
                case 'quiz_correct':
                    // simplified: assuming 1 score = 1 correct answer
                    $totalQuizScore = Score::where('user_id', $user->id)
                        ->whereHas('game', function($q) { $q->where('category', 'quiz'); })
                        ->sum('score');
                    if ($totalQuizScore >= $ach->requirement_value) $unlocked = true;
                    break;
                case 'total_xp':
                    if ($user->xp >= $ach->requirement_value) $unlocked = true;
                    break;
                case 'total_coins':
                    if ($user->coins >= $ach->requirement_value) $unlocked = true;
                    break;
            }

            if ($unlocked) {
                UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $ach->id,
                    'unlocked_at' => now()
                ]);
                
                $user->increment('xp', $ach->xp_reward);
                $user->increment('coins', $ach->coin_reward);

                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'achievement_unlocked',
                    'details' => 'Unlocked achievement: ' . $ach->name
                ]);
            }
        }
    }
}
