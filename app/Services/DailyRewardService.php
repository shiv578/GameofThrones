<?php

namespace App\Services;

use App\Models\DailyReward;
use App\Models\LoginStreak;
use App\Models\RewardHistory;
use App\Models\User;
use App\Models\UserBox;
use App\Models\MysteryBox;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyRewardService
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Get the calendar claim state for a user.
     */
    public function getCalendarState(int $userId): array
    {
        $streak = LoginStreak::firstOrCreate(['user_id' => $userId]);

        // If streak is broken, it would be reset on next claim. For UI rendering, check:
        $isBroken = $streak->isStreakBroken();
        $currentStreakCount = $isBroken ? 0 : $streak->streak_count;

        // Current day in cycle (1 to 7)
        $claimedToday = $streak->claimed_today && !$isBroken;
        
        $currentDayNumber = ($currentStreakCount % 7) + 1;
        if ($claimedToday) {
            // If they claimed today, the "current" active day is the one they just claimed,
            // or if it was day 7, it wraps back.
            $activeDay = ($currentStreakCount - 1) % 7 + 1;
        } else {
            $activeDay = $currentDayNumber;
        }

        $rewards = DailyReward::orderBy('day_number', 'asc')->get();

        $calendar = [];
        foreach ($rewards as $reward) {
            $dayNum = $reward->day_number;
            
            $status = 'locked'; // locked, available, claimed
            if ($dayNum < $activeDay) {
                $status = 'claimed';
            } elseif ($dayNum == $activeDay) {
                $status = $claimedToday ? 'claimed' : 'available';
            }

            $calendar[] = [
                'day_number' => $dayNum,
                'coins_reward' => $reward->coins_reward,
                'diamonds_min' => $reward->diamonds_min,
                'diamonds_max' => $reward->diamonds_max,
                'diamond_chance' => $reward->diamond_chance,
                'box_type' => $reward->box_type,
                'label' => $reward->label ?? "Day {$dayNum}",
                'icon' => $reward->icon,
                'status' => $status,
            ];
        }

        return [
            'streak_count' => $currentStreakCount,
            'longest_streak' => $streak->longest_streak,
            'claimed_today' => $claimedToday,
            'calendar' => $calendar,
        ];
    }

    /**
     * Check streak and claim today's daily reward.
     */
    public function claimReward(int $userId): array
    {
        return DB::transaction(function () use ($userId) {
            $streak = LoginStreak::firstOrCreate(['user_id' => $userId]);

            if (!$streak->canClaimToday()) {
                throw new \Exception("Daily reward has already been claimed today.");
            }

            // Check if streak was broken (missed yesterday)
            if ($streak->isStreakBroken()) {
                $streak->update([
                    'streak_count' => 0,
                    'claimed_today' => false,
                ]);
            }

            // Determine active day number (1-7)
            $dayNumber = ($streak->streak_count % 7) + 1;

            $rewardConfig = DailyReward::where('day_number', $dayNumber)->first();
            if (!$rewardConfig) {
                // Seed fallback reward configuration if database is empty
                $rewardConfig = DailyReward::create([
                    'day_number' => $dayNumber,
                    'coins_reward' => $dayNumber * 50,
                    'diamonds_min' => 0,
                    'diamonds_max' => 2,
                    'diamond_chance' => 30,
                    'box_type' => ($dayNumber === 7) ? 'epic' : null,
                ]);
            }

            // Roll rewards
            $coinsEarned = $rewardConfig->coins_reward;
            $diamondsEarned = $rewardConfig->rollDiamonds();
            $boxGranted = null;

            // Grant mystery box if configured
            if ($rewardConfig->box_type) {
                $mysteryBox = MysteryBox::where('type', $rewardConfig->box_type)->first();
                if ($mysteryBox) {
                    $userBox = UserBox::create([
                        'user_id' => $userId,
                        'mystery_box_id' => $mysteryBox->id,
                        'source' => 'daily_reward',
                        'is_opened' => false,
                    ]);
                    $boxGranted = [
                        'id' => $userBox->id,
                        'name' => $mysteryBox->name,
                        'type' => $mysteryBox->type,
                        'glow_color' => $mysteryBox->glow_color,
                    ];
                }
            }

            // Update streak record
            $newStreakCount = $streak->streak_count + 1;
            $longestStreak = max($streak->longest_streak, $newStreakCount);

            $streak->update([
                'streak_count' => $newStreakCount,
                'longest_streak' => $longestStreak,
                'last_claim_date' => Carbon::today(),
                'claimed_today' => true,
                'total_claims' => $streak->total_claims + 1,
            ]);

            // Sync with User table login attributes
            $user = User::find($userId);
            if ($user) {
                $user->update([
                    'total_login_days' => $user->total_login_days + 1,
                    'last_login_date' => Carbon::today(),
                    'login_streak' => $newStreakCount,
                    'current_streak' => $newStreakCount,
                ]);
            }

            // Credit currencies
            if ($coinsEarned > 0) {
                $this->walletService->credit($userId, 'coins', $coinsEarned, 'daily_reward', $rewardConfig, "Claimed Day {$dayNumber} daily reward");
            }
            if ($diamondsEarned > 0) {
                $this->walletService->credit($userId, 'diamonds', $diamondsEarned, 'daily_reward', $rewardConfig, "Claimed Day {$dayNumber} daily reward");
            }

            // Log to reward history
            RewardHistory::create([
                'user_id' => $userId,
                'source_type' => 'daily_reward',
                'source_id' => $rewardConfig->id,
                'coins_earned' => $coinsEarned,
                'diamonds_earned' => $diamondsEarned,
                'items_earned' => $boxGranted ? [$boxGranted] : null,
                'description' => "Claimed Day {$dayNumber} Daily Reward",
            ]);

            // Fire events
            event(new \App\Events\RewardClaimed($userId, 'daily_reward', [
                'coins' => $coinsEarned,
                'diamonds' => $diamondsEarned,
                'box' => $boxGranted,
            ]));

            // Daily quest progression trigger
            $achievementService = resolve(AchievementService::class);
            $achievementService->checkProgress($userId, 'login', 1);

            return [
                'day_claimed' => $dayNumber,
                'coins' => $coinsEarned,
                'diamonds' => $diamondsEarned,
                'box' => $boxGranted,
                'streak_count' => $newStreakCount,
            ];
        });
    }
}
