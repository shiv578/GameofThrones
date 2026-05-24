<?php

namespace App\Http\Controllers;

use App\Models\PremiumGame;
use App\Models\UserGameUnlock;
use App\Models\UserPurchase;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PremiumGameController extends Controller
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Unlock a premium game using diamonds.
     */
    public function unlock(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;

        try {
            $result = DB::transaction(function () use ($userId, $id) {
                $premiumGame = PremiumGame::where('id', $id)
                    ->where('is_active', true)
                    ->first();

                if (!$premiumGame) {
                    throw new Exception("Premium game not found.");
                }

                // Check if already unlocked
                if ($premiumGame->isUnlockedBy($userId)) {
                    return [
                        'already_unlocked' => true,
                        'message' => "⚔️ This game is already unlocked!",
                    ];
                }

                $price = $premiumGame->price_diamonds;

                // Debit diamonds
                $this->walletService->debit($userId, 'diamonds', $price, 'game_unlock', $premiumGame, "Unlocked Premium Game: {$premiumGame->name}");

                // Create purchase log
                $purchase = UserPurchase::create([
                    'user_id' => $userId,
                    'item_name' => "Premium Game: {$premiumGame->name}",
                    'payment_method' => 'diamonds',
                    'amount_paid' => $price,
                    'status' => 'completed',
                ]);

                // Grant unlock
                UserGameUnlock::create([
                    'user_id' => $userId,
                    'premium_game_id' => $premiumGame->id,
                    'unlock_method' => 'diamonds',
                    'purchase_id' => $purchase->id,
                ]);

                return [
                    'already_unlocked' => false,
                    'message' => "⚔️ Game unlocked successfully! You can now play.",
                    'game_name' => $premiumGame->name,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => $result['message'],
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
