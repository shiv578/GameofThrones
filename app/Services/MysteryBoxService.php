<?php

namespace App\Services;

use App\Models\MysteryBox;
use App\Models\UserBox;
use App\Models\UserInventory;
use App\Models\RewardHistory;
use Illuminate\Support\Facades\DB;

class MysteryBoxService
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Grant a mystery box of a specific type to a user.
     */
    public function grantBox(int $userId, string $boxType, string $source): UserBox
    {
        $box = MysteryBox::where('type', $boxType)->first();
        if (!$box) {
            // Seed a fallback mystery box configuration if database is empty
            $box = MysteryBox::create([
                'type' => $boxType,
                'name' => ucfirst($boxType) . ' Chest',
                'description' => "A mystery box containing valuable rewards.",
                'rarity' => $boxType === 'mythic' ? 'legendary' : ($boxType === 'epic' ? 'epic' : 'rare'),
                'glow_color' => $boxType === 'mythic' ? 'purple' : ($boxType === 'epic' ? 'gold' : 'blue'),
                'min_coins' => 100,
                'max_coins' => 1000,
                'min_diamonds' => 1,
                'max_diamonds' => 5,
                'grants_avatar' => ($boxType === 'mythic'),
                'grants_border' => ($boxType === 'mythic' || $boxType === 'epic'),
            ]);
        }

        return UserBox::create([
            'user_id' => $userId,
            'mystery_box_id' => $box->id,
            'source' => $source,
            'is_opened' => false,
        ]);
    }

    /**
     * Open a mystery box and claim rewards.
     */
    public function openBox(int $userId, int $userBoxId): array
    {
        return DB::transaction(function () use ($userId, $userBoxId) {
            $userBox = UserBox::where('id', $userBoxId)
                ->where('user_id', $userId)
                ->first();

            if (!$userBox) {
                throw new \Exception("Mystery box not found.");
            }

            if (!$userBox->canBeOpened()) {
                throw new \Exception("This mystery box has already been opened.");
            }

            $box = $userBox->mysteryBox;

            // Generate rewards server-side
            $rewards = $box->generateReward();

            $coinsEarned = $rewards['coins'];
            $diamondsEarned = $rewards['diamonds'];
            $itemsEarned = $rewards['items'] ?? [];

            // Update user box state
            $userBox->update([
                'is_opened' => true,
                'opened_at' => now(),
                'reward_coins' => $coinsEarned,
                'reward_diamonds' => $diamondsEarned,
                'reward_items' => $itemsEarned,
            ]);

            // Credit currencies
            if ($coinsEarned > 0) {
                $this->walletService->credit($userId, 'coins', $coinsEarned, 'box_open', $userBox, "Opened {$box->name}");
            }
            if ($diamondsEarned > 0) {
                $this->walletService->credit($userId, 'diamonds', $diamondsEarned, 'box_open', $userBox, "Opened {$box->name}");
            }

            // Create inventory records for cosmetic items
            $savedItems = [];
            foreach ($itemsEarned as $item) {
                $inventoryItem = UserInventory::firstOrCreate([
                    'user_id' => $userId,
                    'item_type' => $item['type'],
                    'item_key' => $item['key'],
                ], [
                    'item_name' => $item['name'],
                    'source' => 'mystery_box',
                    'source_id' => $userBoxId,
                    'is_equipped' => false,
                ]);

                $savedItems[] = [
                    'id' => $inventoryItem->id,
                    'type' => $inventoryItem->item_type,
                    'name' => $inventoryItem->item_name,
                    'key' => $inventoryItem->item_key,
                ];
            }

            // Record in reward history
            RewardHistory::create([
                'user_id' => $userId,
                'source_type' => 'mystery_box',
                'source_id' => $userBox->id,
                'coins_earned' => $coinsEarned,
                'diamonds_earned' => $diamondsEarned,
                'items_earned' => count($savedItems) > 0 ? $savedItems : null,
                'description' => "Opened {$box->name}",
            ]);

            // Fire event
            event(new \App\Events\RewardClaimed($userId, 'mystery_box', [
                'coins' => $coinsEarned,
                'diamonds' => $diamondsEarned,
                'items' => $savedItems,
            ]));

            // Update achievement progress (e.g. open mystery box achievement)
            $achievementService = resolve(AchievementService::class);
            $achievementService->checkProgress($userId, 'open_boxes', 1);

            return [
                'coins' => $coinsEarned,
                'diamonds' => $diamondsEarned,
                'items' => $savedItems,
                'box_name' => $box->name,
                'glow_color' => $box->glow_color,
            ];
        });
    }
}
