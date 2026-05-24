<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MysteryBox;
use App\Models\UserBox;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    /**
     * Claim daily login reward
     */
    public function claimDaily(Request $request)
    {
        $user = $request->user();

        // Determine the current reward period reset time (5:30 AM daily)
        $resetTime = now()->copy()->startOfDay()->addHours(5)->addMinutes(30);
        if (now()->lt($resetTime)) {
            // Before 5:30 AM today, so the active period started yesterday at 5:30 AM
            $resetTime = $resetTime->subDay();
        }

        // Check if already claimed in this reward period
        if ($user->last_reward_claimed_at && $user->last_reward_claimed_at->gte($resetTime)) {
            return response()->json([
                'success' => false,
                'message' => 'Daily reward already claimed today.'
            ], 400);
        }

        $coinsReward = 200;
        $diamondsReward = 1;

        DB::beginTransaction();
        try {
            // Ensure a basic daily mystery box exists in DB
            $mysteryBox = MysteryBox::firstOrCreate(
                ['type' => 'daily_login'],
                [
                    'name' => 'Daily Mystery Box',
                    'description' => 'A reward for logging in daily.',
                    'rarity' => 'common',
                    'glow_color' => '#ffffff',
                    'min_coins' => 100,
                    'max_coins' => 500,
                    'min_diamonds' => 1,
                    'max_diamonds' => 5,
                    'grants_avatar' => false,
                    'grants_border' => false,
                    'availability' => 'always',
                    'is_active' => true,
                ]
            );

            // Update user balances
            $user->coins += $coinsReward;
            $user->diamonds += $diamondsReward;
            $user->last_reward_claimed_at = now();
            $user->save();

            // Give a mystery box
            UserBox::create([
                'user_id' => $user->id,
                'mystery_box_id' => $mysteryBox->id,
                'source' => 'daily_reward',
                'is_opened' => false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Daily Reward Claimed!',
                'coins' => $coinsReward,
                'diamonds' => $diamondsReward,
                'box' => 1,
                'new_coins_balance' => $user->coins,
                'new_diamonds_balance' => $user->diamonds
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to claim reward. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Open a mystery box
     */
    public function openMysteryBox(Request $request)
    {
        $user = $request->user();

        $userBox = UserBox::where('user_id', $user->id)
            ->where('is_opened', false)
            ->with('mysteryBox')
            ->first();

        if (!$userBox) {
            return response()->json([
                'success' => false,
                'message' => 'No mystery boxes available.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $mysteryBox = $userBox->mysteryBox;
            $reward = $mysteryBox->generateReward();

            // Apply rewards to user
            $user->coins += $reward['coins'];
            $user->diamonds += $reward['diamonds'];
            $user->save();

            // Mark box as opened
            $userBox->is_opened = true;
            $userBox->opened_at = now();
            $userBox->reward_coins = $reward['coins'];
            $userBox->reward_diamonds = $reward['diamonds'];
            $userBox->reward_items = $reward['items'];
            $userBox->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mystery Box Opened!',
                'coins' => $reward['coins'],
                'diamonds' => $reward['diamonds'],
                'new_coins_balance' => $user->coins,
                'new_diamonds_balance' => $user->diamonds
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to open mystery box. ' . $e->getMessage()
            ], 500);
        }
    }
}
