<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Models\DailyReward;
use App\Models\MysteryBox;
use App\Models\ShopItem;
use App\Models\Achievement;
use App\Models\Notification;
use App\Models\Wallet;
use App\Models\LoginStreak;
use App\Models\UserBox;
use App\Models\UserInventory;
use App\Models\UserPurchase;
use App\Models\AchievementProgress;
use App\Services\WalletService;
use App\Services\DailyRewardService;
use App\Services\MysteryBoxService;
use App\Services\AchievementService;
use App\Services\ShopService;
use App\Services\NotificationService;
use App\Services\RankService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameEcosystemServicesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected WalletService $walletService;
    protected DailyRewardService $dailyRewardService;
    protected MysteryBoxService $mysteryBoxService;
    protected AchievementService $achievementService;
    protected ShopService $shopService;
    protected NotificationService $notificationService;
    protected RankService $rankService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::create([
            'name' => 'Gamer Stark',
            'email' => 'stark@winterfell.com',
            'password' => bcrypt('password'),
            'house' => 'Stark',
            'character_class' => 'Warrior',
            'xp' => 0,
            'coins' => 100,
            'theme_preference' => 'ice',
        ]);

        // Resolve services
        $this->walletService = resolve(WalletService::class);
        $this->dailyRewardService = resolve(DailyRewardService::class);
        $this->mysteryBoxService = resolve(MysteryBoxService::class);
        $this->achievementService = resolve(AchievementService::class);
        $this->shopService = resolve(ShopService::class);
        $this->notificationService = resolve(NotificationService::class);
        $this->rankService = resolve(RankService::class);
    }

    public function test_wallet_service_operations(): void
    {
        // Check wallet auto-creation
        $balance = $this->walletService->getBalance($this->user->id);
        $this->assertEquals(0, $balance['coins']);
        $this->assertEquals(0, $balance['diamonds']);

        // Credit coins
        $this->walletService->credit($this->user->id, 'coins', 500, 'test_source');
        $balance = $this->walletService->getBalance($this->user->id);
        $this->assertEquals(500, $balance['coins']);

        // Credit diamonds
        $this->walletService->credit($this->user->id, 'diamonds', 10, 'test_source');
        $balance = $this->walletService->getBalance($this->user->id);
        $this->assertEquals(10, $balance['diamonds']);

        // Debit coins
        $this->walletService->debit($this->user->id, 'coins', 200, 'test_source');
        $balance = $this->walletService->getBalance($this->user->id);
        $this->assertEquals(300, $balance['coins']);

        // Assert transaction log exists
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->user->id,
            'type' => 'debit',
            'currency' => 'coins',
            'amount' => 200,
            'source' => 'test_source',
        ]);

        // Debit too much (expect exception)
        $this->expectException(\Exception::class);
        $this->walletService->debit($this->user->id, 'coins', 1000, 'test_source');
    }

    public function test_daily_reward_claiming(): void
    {
        // Seed a DailyReward for Day 1
        DailyReward::create([
            'day_number' => 1,
            'coins_reward' => 200,
            'diamonds_min' => 2,
            'diamonds_max' => 5,
            'diamond_chance' => 100, // 100% chance to test deterministically
            'box_type' => null,
            'label' => 'Day 1 Test',
        ]);

        // Claim reward
        $result = $this->dailyRewardService->claimReward($this->user->id);

        $this->assertEquals(1, $result['day_claimed']);
        $this->assertEquals(200, $result['coins']);
        $this->assertGreaterThanOrEqual(2, $result['diamonds']);
        $this->assertLessThanOrEqual(5, $result['diamonds']);

        // Wallet balance check
        $balance = $this->walletService->getBalance($this->user->id);
        $this->assertEquals(200, $balance['coins']);

        // Assert streak count updated
        $streak = LoginStreak::where('user_id', $this->user->id)->first();
        $this->assertEquals(1, $streak->streak_count);
        $this->assertTrue($streak->claimed_today);

        // Attempting to claim again today should fail
        $this->expectException(\Exception::class);
        $this->dailyRewardService->claimReward($this->user->id);
    }

    public function test_mystery_box_service(): void
    {
        // Create box type
        $box = MysteryBox::create([
            'type' => 'epic',
            'name' => 'Epic Winter Box',
            'description' => 'Epic chest containing coins and borders',
            'rarity' => 'epic',
            'glow_color' => 'gold',
            'min_coins' => 100,
            'max_coins' => 200,
            'min_diamonds' => 2,
            'max_diamonds' => 5,
            'grants_avatar' => false,
            'grants_border' => true,
            'availability' => 'always',
        ]);

        // Grant box to user
        $userBox = $this->mysteryBoxService->grantBox($this->user->id, 'epic', 'test');
        $this->assertDatabaseHas('user_boxes', [
            'id' => $userBox->id,
            'user_id' => $this->user->id,
            'is_opened' => false,
        ]);

        // Open box
        $result = $this->mysteryBoxService->openBox($this->user->id, $userBox->id);
        $this->assertGreaterThanOrEqual(100, $result['coins']);
        $this->assertGreaterThanOrEqual(2, $result['diamonds']);

        // Check user box marked as opened
        $userBox->refresh();
        $this->assertTrue($userBox->is_opened);
        $this->assertNotNull($userBox->opened_at);
    }

    public function test_achievement_progress_and_claiming(): void
    {
        // Create an achievement
        $achievement = Achievement::create([
            'name' => 'Warming Up',
            'description' => 'Play 5 games',
            'icon' => 'game-icon',
            'xp_reward' => 100,
            'coin_reward' => 50,
            'diamond_reward' => 5,
            'requirement_type' => 'play_games',
            'requirement_value' => 5,
            'progress_target' => 5,
            'category' => 'games',
            'rarity' => 'common',
            'is_active' => true,
        ]);

        // Update progress
        $this->achievementService->checkProgress($this->user->id, 'play_games', 2);
        $progress = AchievementProgress::where('user_id', $this->user->id)->first();
        $this->assertEquals(2, $progress->current_progress);
        $this->assertFalse($progress->is_completed);

        // Reach completion threshold
        $this->achievementService->checkProgress($this->user->id, 'play_games', 3);
        $progress->refresh();
        $this->assertEquals(5, $progress->current_progress);
        $this->assertTrue($progress->is_completed);
        $this->assertFalse($progress->is_claimed);

        // Claim rewards
        $result = $this->achievementService->claimReward($this->user->id, $achievement->id);
        $this->assertEquals(50, $result['coins']);
        $this->assertEquals(5, $result['diamonds']);
        $this->assertEquals(100, $result['xp']);

        $progress->refresh();
        $this->assertTrue($progress->is_claimed);

        // Wallet checks
        $balance = $this->walletService->getBalance($this->user->id);
        $this->assertEquals(50, $balance['coins']);
        $this->assertEquals(5, $balance['diamonds']);

        // XP check
        $this->user->refresh();
        $this->assertEquals(100, $this->user->xp);
    }

    public function test_shop_purchase_service(): void
    {
        // Set user coins to 500
        $this->walletService->credit($this->user->id, 'coins', 500, 'admin');

        // Create a shop item
        $item = ShopItem::create([
            'name' => 'Iron Emblem Avatar',
            'category' => 'avatar',
            'price_coins' => 200,
            'price_diamonds' => null,
            'is_limited' => true,
            'stock' => 5,
            'item_data' => ['key' => 'iron_emblem'],
            'is_active' => true,
        ]);

        // Purchase item
        $result = $this->shopService->purchaseItem($this->user->id, $item->id, 'coins');
        $this->assertTrue($result['success']);
        $this->assertEquals('Iron Emblem Avatar', $result['item_name']);

        // Assert coins debited (500 - 200 = 300)
        $balance = $this->walletService->getBalance($this->user->id);
        $this->assertEquals(300, $balance['coins']);

        // Assert stock decremented (5 -> 4)
        $item->refresh();
        $this->assertEquals(4, $item->stock);

        // Assert item delivered to user inventory
        $this->assertDatabaseHas('user_inventories', [
            'user_id' => $this->user->id,
            'item_type' => 'avatar',
            'item_key' => 'iron_emblem',
        ]);
    }

    public function test_notifications_service(): void
    {
        // Send notification
        $notification = $this->notificationService->send($this->user->id, 'streak_alert', [
            'title' => 'Streak Warning!',
            'message' => 'Your streak will reset in 2 hours!',
        ]);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'user_id' => $this->user->id,
            'type' => 'streak_alert',
            'title' => 'Streak Warning!',
            'read_at' => null,
        ]);

        // Get unread
        $unread = $this->notificationService->getUnread($this->user->id);
        $this->assertCount(1, $unread);

        // Mark read
        $this->notificationService->markAsRead($this->user->id, $notification->id);
        $unread = $this->notificationService->getUnread($this->user->id);
        $this->assertCount(0, $unread);
    }

    public function test_rank_service_progression(): void
    {
        // Silver rank threshold is 1000 XP
        $rankBefore = $this->rankService->determineRank(500);
        $this->assertEquals('Bronze', $rankBefore);

        $rankAfter = $this->rankService->determineRank(1200);
        $this->assertEquals('Silver', $rankAfter);

        // Set user XP and check promotion triggers
        $this->user->update(['xp' => 1200]);
        $newRank = $this->rankService->checkRankUpdate($this->user->id);
        $this->assertEquals('Silver', $newRank);

        // User rank updated in DB
        $this->user->refresh();
        $this->assertEquals('Silver', $this->user->rank);

        // Received silver rank reward (coins 200, diamonds 5)
        $balance = $this->walletService->getBalance($this->user->id);
        $this->assertEquals(200, $balance['coins']);
        $this->assertEquals(5, $balance['diamonds']);
    }
}
