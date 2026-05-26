<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Score;
use App\Models\Achievement;
use App\Models\AchievementProgress;
use App\Models\Game;
use Illuminate\Support\Carbon;

class NotificationService
{
    /**
     * Send a notification to a user.
     */
    public function send(int $userId, string $type, array $data): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $data['title'] ?? 'New Notification',
            'message' => $data['message'] ?? '',
            'read_at' => null,
        ]);
    }

    /**
     * Send a notification only if a similar unread one doesn't already exist (dedup).
     */
    public function sendIfNew(int $userId, string $type, array $data): ?Notification
    {
        $exists = Notification::where('user_id', $userId)
            ->where('type', $type)
            ->where('title', $data['title'] ?? 'New Notification')
            ->whereNull('read_at')
            ->where('created_at', '>=', now()->subHours(12))
            ->exists();

        if ($exists) {
            return null;
        }

        return $this->send($userId, $type, $data);
    }

    /**
     * Generate smart, contextual notifications based on user's game state.
     */
    public function generateSmartNotifications(int $userId): void
    {
        $user = User::find($userId);
        if (!$user) return;

        $totalGamesPlayed = Score::where('user_id', $userId)->count();
        $lastPlayed = Score::where('user_id', $userId)->latest()->first();
        $hoursSinceLastGame = $lastPlayed ? now()->diffInHours($lastPlayed->created_at) : 999;

        // ─── 1. Newcomer encouragement ───
        if ($totalGamesPlayed < 5) {
            $remaining = 5 - $totalGamesPlayed;
            $this->sendIfNew($userId, 'achievement', [
                'title' => '🗡️ First Blood Awaits',
                'message' => "Play {$remaining} more game" . ($remaining > 1 ? 's' : '') . " to unlock the First Blood achievement! Every warrior starts somewhere.",
            ]);
        }

        // ─── 2. Comeback reminder (inactive 24h+) ───
        if ($hoursSinceLastGame >= 24 && $hoursSinceLastGame < 168) {
            $this->sendIfNew($userId, 'reminder', [
                'title' => '🏰 Your Kingdom Awaits',
                'message' => "Commander, the realm has been quiet without you. Return to sharpen your strategic mind and defend your honor!",
            ]);
        }

        // ─── 3. Long absence (7+ days) ───
        if ($hoursSinceLastGame >= 168) {
            $this->sendIfNew($userId, 'reminder', [
                'title' => '⚔️ The Realm Needs You',
                'message' => "It's been over a week since your last conquest. Your rivals grow stronger — reclaim your throne now!",
            ]);
        }

        // ─── 4. Close to next level ───
        $xpPerLevel = 1000;
        $currentLevelBaseXp = ($user->level - 1) * $xpPerLevel;
        $xpInCurrentLevel = max(0, $user->xp - $currentLevelBaseXp);
        $xpRemaining = $xpPerLevel - $xpInCurrentLevel;
        $progressPercent = ($xpInCurrentLevel / $xpPerLevel) * 100;

        if ($progressPercent >= 75 && $xpRemaining > 0) {
            $nextLevel = $user->level + 1;
            $this->sendIfNew($userId, 'level_up', [
                'title' => '⚡ Almost There, Commander!',
                'message' => "You're just {$xpRemaining} XP from Level {$nextLevel}! Play one more round to ascend and unlock new powers.",
            ]);
        }

        // ─── 5. Unclaimed daily reward ───
        $now = now('Asia/Kolkata');
        $resetTime = $now->copy()->startOfDay()->addHours(5)->addMinutes(30);
        if ($now->lt($resetTime)) {
            $resetTime = $resetTime->subDay();
        }
        $canClaim = !$user->last_reward_claimed_at || $user->last_reward_claimed_at->lt($resetTime);

        if ($canClaim) {
            $this->sendIfNew($userId, 'reward', [
                'title' => '🎁 Daily Provisions Ready',
                'message' => "Your royal provisions are waiting! Claim your daily reward before the sun sets on the realm.",
            ]);
        }

        // ─── 6. Unexplored game categories ───
        $allCategories = Game::select('category')->distinct()->pluck('category')->toArray();
        $playedCategories = Score::where('user_id', $userId)
            ->join('games', 'scores.game_id', '=', 'games.id')
            ->select('games.category')
            ->distinct()
            ->pluck('category')
            ->toArray();

        $unexplored = array_diff($allCategories, $playedCategories);

        $categoryTips = [
            'brain' => ['🧠 Train Your Mind', 'Brain games strengthen pattern recognition and analytical thinking. Try an IQ Challenge to boost your cognitive mastery!'],
            'puzzle' => ['🧩 Puzzle Mastery', 'Puzzle games improve spatial reasoning and problem-solving skills. Dive into Maze Escape and test your wits!'],
            'quiz' => ['📜 Knowledge is Power', 'Quiz games expand your knowledge and improve recall speed. Try the History Quiz and prove your wisdom!'],
            'strategy' => ['♟️ Strategic Warfare', 'Strategy games sharpen your planning and decision-making. Enter the Kingdom Defense arena!'],
            'memory' => ['👁️ Sharpen Your Memory', 'Memory games boost focus and retention. Try Memory Flip and train your mind to remember every detail!'],
            'toys' => ['🎮 Explore Toys Arena', 'The Toys Games arena has fun challenges waiting for you. Jump in and discover something new!'],
        ];

        if (!empty($unexplored)) {
            $category = $unexplored[array_rand($unexplored)];
            $tip = $categoryTips[$category] ?? ['🎮 New Arena Awaits', "You haven't explored the {$category} games yet. Try one today!"];
            $this->sendIfNew($userId, 'tip', [
                'title' => $tip[0],
                'message' => $tip[1],
            ]);
        }

        // ─── 7. Achievement progress notifications ───
        $achievements = Achievement::where('is_active', true)->get();
        foreach ($achievements as $achievement) {
            $progress = AchievementProgress::where('user_id', $userId)
                ->where('achievement_id', $achievement->id)
                ->first();

            if (!$progress) continue;

            $target = $achievement->progress_target ?? $achievement->requirement_value ?? 0;
            if ($target <= 0) continue;

            $current = $progress->current_value ?? 0;
            $percentDone = ($current / $target) * 100;
            $remaining = $target - $current;

            // Only notify when they're 50%+ done but not yet completed
            if ($percentDone >= 50 && $percentDone < 100 && $remaining > 0) {
                $this->sendIfNew($userId, 'achievement', [
                    'title' => "🏆 {$achievement->name} — Almost Unlocked!",
                    'message' => "You're {$remaining} away from unlocking the \"{$achievement->name}\" badge. Keep pushing, Commander!",
                ]);
                break; // Only one achievement notification per cycle
            }
        }

        // ─── 8. Login streak warning ───
        if ($user->current_streak >= 3) {
            $lastLogin = $user->last_login_date;
            if ($lastLogin && now()->diffInHours($lastLogin) >= 20) {
                $this->sendIfNew($userId, 'streak', [
                    'title' => "🔥 {$user->current_streak}-Day Streak at Risk!",
                    'message' => "Your {$user->current_streak}-day login streak is about to break! Log in soon to keep the flame alive.",
                ]);
            }
        }

        // ─── 9. Personal best challenge ───
        if ($lastPlayed && $totalGamesPlayed >= 3) {
            $lastGame = Game::find($lastPlayed->game_id);
            if ($lastGame) {
                $highScore = Score::where('user_id', $userId)
                    ->where('game_id', $lastGame->id)
                    ->max('score');

                if ($highScore > 0) {
                    $this->sendIfNew($userId, 'challenge', [
                        'title' => "📈 Beat Your Record!",
                        'message' => "Your best on {$lastGame->name} is {$highScore} points. Think you can top it? Challenge accepted!",
                    ]);
                }
            }
        }

        // ─── 10. Milestone celebrations ───
        $milestones = [10, 25, 50, 100, 250, 500];
        foreach ($milestones as $milestone) {
            if ($totalGamesPlayed >= $milestone) {
                $this->sendIfNew($userId, 'milestone', [
                    'title' => "🎉 {$milestone} Games Conquered!",
                    'message' => "You've completed {$milestone} games across the realm! Your legend grows stronger with every battle.",
                ]);
            }
        }
    }

    /**
     * Generate a post-game notification.
     */
    public function sendPostGameNotification(int $userId, string $gameName, int $xpEarned, int $score, bool $isNewHighScore): void
    {
        if ($isNewHighScore) {
            $this->send($userId, 'achievement', [
                'title' => "🏆 New Personal Best!",
                'message' => "You scored {$score} on {$gameName} — a new record! Your mastery over this realm grows stronger.",
            ]);
        }

        // Check proximity to next level after earning XP
        $user = User::find($userId);
        if ($user) {
            $xpPerLevel = 1000;
            $currentLevelBaseXp = ($user->level - 1) * $xpPerLevel;
            $xpInCurrentLevel = max(0, $user->xp - $currentLevelBaseXp);
            $xpRemaining = $xpPerLevel - $xpInCurrentLevel;

            if ($xpRemaining <= 200 && $xpRemaining > 0) {
                $nextLevel = $user->level + 1;
                $this->sendIfNew($userId, 'level_up', [
                    'title' => "⚡ Level {$nextLevel} Within Reach!",
                    'message' => "Just {$xpRemaining} XP to go! One more game and you'll ascend to Level {$nextLevel}.",
                ]);
            }
        }
    }

    /**
     * Get unread notifications for a user.
     */
    public function getUnread(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all notifications for a user.
     */
    public function getAll(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(int $userId, int $notificationId): bool
    {
        $notification = Notification::where('user_id', $userId)
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            return $notification->update(['read_at' => now()]);
        }

        return false;
    }

    /**
     * Mark all notifications for a user as read.
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Delete all notifications for a user.
     */
    public function clearAll(int $userId): int
    {
        return Notification::where('user_id', $userId)->delete();
    }
}
