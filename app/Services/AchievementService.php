<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\AchievementProgress;
use App\Models\RewardHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AchievementService
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Check and increment achievement progress for a specific requirement type.
     */
    public function checkProgress(int $userId, string $requirementType, int $increment = 1): void
    {
        $achievements = Achievement::where('requirement_type', $requirementType)
            ->where('is_active', true)
            ->get();

        foreach ($achievements as $achievement) {
            $progress = AchievementProgress::firstOrCreate([
                'user_id' => $userId,
                'achievement_id' => $achievement->id,
            ], [
                'current_progress' => 0,
                'is_completed' => false,
                'is_claimed' => false,
            ]);

            if ($progress->is_completed) {
                continue;
            }

            $progress->incrementProgress($increment);

            // If newly completed, fire event
            if ($progress->is_completed) {
                event(new \App\Events\AchievementUnlocked($userId, $achievement));
                
                // Add DB notification
                $notificationService = resolve(NotificationService::class);
                $notificationService->send($userId, 'achievement_completed', [
                    'achievement_id' => $achievement->id,
                    'title' => 'Achievement Completed!',
                    'message' => "⚔️ You unlocked: {$achievement->name}",
                    'coins' => $achievement->coin_reward,
                    'diamonds' => $achievement->diamond_reward,
                    'xp' => $achievement->xp_reward,
                ]);
            }
        }
    }

    /**
     * Claim rewards for a completed achievement.
     */
    public function claimReward(int $userId, int $achievementId): array
    {
        return DB::transaction(function () use ($userId, $achievementId) {
            $progress = AchievementProgress::where('user_id', $userId)
                ->where('achievement_id', $achievementId)
                ->first();

            if (!$progress) {
                throw new \Exception("No progress record found for this achievement.");
            }

            if (!$progress->canClaim()) {
                throw new \Exception("Achievement is either not completed or rewards have already been claimed.");
            }

            $achievement = $progress->achievement;

            // Mark as claimed
            $progress->update([
                'is_claimed' => true,
                'claimed_at' => now(),
            ]);

            // Sync with old user_achievements table for backwards compatibility
            $user = User::find($userId);
            if ($user) {
                $user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);
                
                // Grant XP
                if ($achievement->xp_reward > 0) {
                    $user->increment('xp', $achievement->xp_reward);
                    // Rank update check
                    $rankService = resolve(RankService::class);
                    $rankService->checkRankUpdate($userId);
                }
            }

            // Credit Currencies
            if ($achievement->coin_reward > 0) {
                $this->walletService->credit($userId, 'coins', $achievement->coin_reward, 'achievement', $achievement, "Unlocked achievement: {$achievement->name}");
            }
            if ($achievement->diamond_reward > 0) {
                $this->walletService->credit($userId, 'diamonds', $achievement->diamond_reward, 'achievement', $achievement, "Unlocked achievement: {$achievement->name}");
            }

            // Log to reward history
            RewardHistory::create([
                'user_id' => $userId,
                'source_type' => 'achievement',
                'source_id' => $achievement->id,
                'coins_earned' => $achievement->coin_reward,
                'diamonds_earned' => $achievement->diamond_reward,
                'xp_earned' => $achievement->xp_reward,
                'description' => "Claimed rewards for achievement: {$achievement->name}",
            ]);

            // Fire event
            event(new \App\Events\RewardClaimed($userId, 'achievement', [
                'coins' => $achievement->coin_reward,
                'diamonds' => $achievement->diamond_reward,
                'xp' => $achievement->xp_reward,
            ]));

            return [
                'coins' => $achievement->coin_reward,
                'diamonds' => $achievement->diamond_reward,
                'xp' => $achievement->xp_reward,
                'name' => $achievement->name,
            ];
        });
    }
}
