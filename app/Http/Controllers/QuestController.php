<?php

namespace App\Http\Controllers;

use App\Models\UserQuest;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Exception;

class QuestController extends Controller
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Display the active quests page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Fetch active daily/weekly quests assigned to user
        $quests = UserQuest::with('dailyQuest')
            ->where('user_id', $user->id)
            ->orderBy('is_completed', 'desc')
            ->orderBy('is_claimed', 'asc')
            ->get();

        return view('quests.index', compact('quests'));
    }

    /**
     * Claim rewards for a completed quest.
     */
    public function claim(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;

        try {
            $result = DB::transaction(function () use ($userId, $id) {
                $userQuest = UserQuest::where('id', $id)
                    ->where('user_id', $userId)
                    ->first();

                if (!$userQuest) {
                    throw new Exception("Quest progress record not found.");
                }

                if (!$userQuest->canClaim()) {
                    throw new Exception("Quest is not completed or has already been claimed.");
                }

                $quest = $userQuest->dailyQuest;

                // Mark claimed
                $userQuest->update([
                    'is_claimed' => true,
                    'claimed_at' => now(),
                ]);

                // Credit rewards
                if ($quest->reward_coins > 0) {
                    $this->walletService->credit($userId, 'coins', $quest->reward_coins, 'quest', $userQuest, "Completed Quest: {$quest->name}");
                }
                if ($quest->reward_diamonds > 0) {
                    $this->walletService->credit($userId, 'diamonds', $quest->reward_diamonds, 'quest', $userQuest, "Completed Quest: {$quest->name}");
                }
                if ($quest->reward_xp > 0) {
                    $user = $userQuest->user;
                    $user->increment('xp', $quest->reward_xp);
                    
                    // Check rank promotion
                    $rankService = resolve(\App\Services\RankService::class);
                    $rankService->checkRankUpdate($userId);
                }

                // Fire event
                event(new \App\Events\RewardClaimed($userId, 'quest', [
                    'coins' => $quest->reward_coins,
                    'diamonds' => $quest->reward_diamonds,
                    'xp' => $quest->reward_xp,
                ]));

                return [
                    'coins' => $quest->reward_coins,
                    'diamonds' => $quest->reward_diamonds,
                    'xp' => $quest->reward_xp,
                    'name' => $quest->name,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => "⚔️ Quest rewards claimed successfully!",
                'data' => $result,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
