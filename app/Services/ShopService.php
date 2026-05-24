<?php

namespace App\Services;

use App\Models\ShopItem;
use App\Models\UserPurchase;
use App\Models\UserInventory;
use App\Models\PremiumGame;
use App\Models\UserGameUnlock;
use Illuminate\Support\Facades\DB;
use Exception;

class ShopService
{
    protected WalletService $walletService;
    protected MysteryBoxService $mysteryBoxService;

    public function __construct(WalletService $walletService, MysteryBoxService $mysteryBoxService)
    {
        $this->walletService = $walletService;
        $this->mysteryBoxService = $mysteryBoxService;
    }

    /**
     * Purchase a shop item using in-game currencies (coins/diamonds).
     */
    public function purchaseItem(int $userId, int $itemId, string $currency): array
    {
        if (!in_array($currency, ['coins', 'diamonds'])) {
            throw new Exception("Invalid payment currency: {$currency}.");
        }

        return DB::transaction(function () use ($userId, $itemId, $currency) {
            $item = ShopItem::where('id', $itemId)->lockForUpdate()->first();

            if (!$item || !$item->is_active) {
                throw new Exception("Shop item is not available.");
            }

            // Check availability dates
            if ($item->available_from && $item->available_from->isFuture()) {
                throw new Exception("Shop item is not yet available.");
            }
            if ($item->expires_at && $item->expires_at->isPast()) {
                throw new Exception("Shop item has expired.");
            }

            // Check stock
            if (!$item->isInStock()) {
                throw new Exception("Shop item is out of stock.");
            }

            // Determine price
            $price = ($currency === 'coins') ? $item->price_coins : $item->price_diamonds;
            if ($price === null || $price < 0) {
                throw new Exception("This item cannot be purchased with {$currency}.");
            }

            // Debit user wallet
            $this->walletService->debit($userId, $currency, $price, 'shop_purchase', $item, "Purchased shop item: {$item->name}");

            // Create purchase record
            $purchase = UserPurchase::create([
                'user_id' => $userId,
                'shop_item_id' => $item->id,
                'item_name' => $item->name,
                'payment_method' => $currency,
                'amount_paid' => $price,
                'status' => 'completed',
            ]);

            // Decrement stock if limited
            if ($item->is_limited && $item->stock !== null) {
                $item->decrement('stock', 1);
            }

            // Deliver the item
            $deliveryDetails = $this->deliverItem($userId, $item, $purchase);

            // Fire purchase completed event
            event(new \App\Events\PurchaseCompleted($purchase));

            return [
                'success' => true,
                'item_name' => $item->name,
                'price' => $price,
                'currency' => $currency,
                'delivery' => $deliveryDetails,
            ];
        });
    }

    /**
     * Deliver the purchased item to the user.
     */
    protected function deliverItem(int $userId, ShopItem $item, UserPurchase $purchase): array
    {
        $category = $item->category;
        $itemData = $item->item_data ?? [];

        switch ($category) {
            case 'avatar':
            case 'border':
            case 'theme':
            case 'badge':
            case 'title':
                $inventory = UserInventory::firstOrCreate([
                    'user_id' => $userId,
                    'item_type' => $category,
                    'item_key' => $itemData['key'] ?? 'item_' . $item->id,
                ], [
                    'item_name' => $item->name,
                    'item_data' => $itemData,
                    'source' => 'shop',
                    'source_id' => $purchase->id,
                    'is_equipped' => false,
                ]);

                return [
                    'type' => 'inventory',
                    'id' => $inventory->id,
                    'key' => $inventory->item_key,
                ];

            case 'mystery_box':
                $boxType = $itemData['box_type'] ?? 'mystery';
                $userBox = $this->mysteryBoxService->grantBox($userId, $boxType, 'shop');

                return [
                    'type' => 'mystery_box',
                    'id' => $userBox->id,
                    'box_type' => $boxType,
                ];

            case 'premium_game':
                $gameSlug = $itemData['game_slug'] ?? null;
                if (!$gameSlug) {
                    throw new Exception("Premium game slug not configured for this shop item.");
                }

                $premiumGame = PremiumGame::whereHas('game', function ($q) use ($gameSlug) {
                    $q->where('slug', $gameSlug);
                })->first();

                if (!$premiumGame) {
                    throw new Exception("Premium game configuration not found.");
                }

                $unlock = UserGameUnlock::firstOrCreate([
                    'user_id' => $userId,
                    'premium_game_id' => $premiumGame->id,
                ], [
                    'unlock_method' => $purchase->payment_method,
                    'purchase_id' => $purchase->id,
                ]);

                return [
                    'type' => 'premium_game',
                    'id' => $unlock->id,
                    'game_slug' => $gameSlug,
                ];

            case 'coin_pack':
                $coinsAmount = $itemData['coins_amount'] ?? 0;
                if ($coinsAmount > 0) {
                    $this->walletService->credit($userId, 'coins', $coinsAmount, 'shop_purchase', $purchase, "Received coins from bundle/pack: {$item->name}");
                }
                return [
                    'type' => 'coins',
                    'amount' => $coinsAmount,
                ];

            case 'diamond_pack':
                $diamondsAmount = $itemData['diamonds_amount'] ?? 0;
                if ($diamondsAmount > 0) {
                    $this->walletService->credit($userId, 'diamonds', $diamondsAmount, 'shop_purchase', $purchase, "Received diamonds from bundle/pack: {$item->name}");
                }
                return [
                    'type' => 'diamonds',
                    'amount' => $diamondsAmount,
                ];

            default:
                throw new Exception("Unsupported delivery category: {$category}.");
        }
    }
}
