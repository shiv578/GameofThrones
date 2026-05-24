<?php

namespace App\Services;

use App\Models\User;
use App\Models\RewardHistory;
use Illuminate\Support\Facades\DB;

class RankService
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Determine rank name based on user's XP.
     */
    public function determineRank(int $xp): string
    {
        if ($xp >= 100000) {
            return 'Legendary';
        } elseif ($xp >= 40000) {
            return 'Diamond';
        } elseif ($xp >= 15000) {
            return 'Platinum';
        } elseif ($xp >= 5000) {
            return 'Gold';
        } elseif ($xp >= 1000) {
            return 'Silver';
        } else {
            return 'Bronze';
        }
    }

    /**
     * Check if a user's rank needs updating based on current XP.
     */
    public function checkRankUpdate(int $userId): ?string
    {
        return DB::transaction(function () use ($userId) {
            $user = User::find($userId);
            if (!$user) return null;

            $newRank = $this->determineRank($user->xp);
            $oldRank = $user->rank ?? 'Bronze';

            if ($newRank !== $oldRank) {
                // Update rank
                $user->update(['rank' => $newRank]);

                // Determine rank up rewards
                $rewards = $this->getRankUpRewards($newRank);

                if ($rewards['coins'] > 0) {
                    $this->walletService->credit($userId, 'coins', $rewards['coins'], 'rank_up', null, "Promoted to {$newRank}");
                }
                if ($rewards['diamonds'] > 0) {
                    $this->walletService->credit($userId, 'diamonds', $rewards['diamonds'], 'rank_up', null, "Promoted to {$newRank}");
                }

                // Log reward history
                RewardHistory::create([
                    'user_id' => $userId,
                    'source_type' => 'rank_up',
                    'coins_earned' => $rewards['coins'],
                    'diamonds_earned' => $rewards['diamonds'],
                    'description' => "Attained the rank of {$newRank}",
                ]);

                // Send notification
                $notificationService = resolve(NotificationService::class);
                $notificationService->send($userId, 'rank_up', [
                    'title' => '🛡️ Rank Up Promotion!',
                    'message' => "Congratulations! You have been promoted to the **{$newRank}** Rank. Received +{$rewards['coins']} Coins and +{$rewards['diamonds']} Diamonds!",
                ]);

                return $newRank;
            }

            return null;
        });
    }

    /**
     * Define rewards for attaining each rank.
     */
    protected function getRankUpRewards(string $rank): array
    {
        switch ($rank) {
            case 'Silver':
                return ['coins' => 200, 'diamonds' => 5];
            case 'Gold':
                return ['coins' => 500, 'diamonds' => 15];
            case 'Platinum':
                return ['coins' => 1000, 'diamonds' => 30];
            case 'Diamond':
                return ['coins' => 2500, 'diamonds' => 75];
            case 'Legendary':
                return ['coins' => 5000, 'diamonds' => 150];
            default:
                return ['coins' => 0, 'diamonds' => 0];
        }
    }
}
