<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Theme
    Route::post('/theme/toggle', [\App\Http\Controllers\ThemeController::class, 'toggle'])->name('theme.toggle');
    
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Games
    Route::get('/all-games', function () {
        $games = \App\Models\Game::all()->map(function ($game) {
            $played = \App\Models\Score::where('user_id', auth()->id())
                ->where('game_id', $game->id)
                ->count();
            $game->level = min(100, floor($played / 15) + 1);
            $game->progress_to_next = ($played % 15) / 15 * 100;
            return $game;
        });
        return view('all-games', compact('games'));
    })->name('all.games');
      Route::get('/games', [\App\Http\Controllers\GameController::class, 'index'])->name('games.index');
    Route::get('/games/{slug}', [\App\Http\Controllers\GameController::class, 'show'])->name('games.show');
    Route::post('/games/{slug}/score', [\App\Http\Controllers\GameController::class, 'saveScore'])->name('games.score');
     
           // Achievements
    Route::get('/achievements', [\App\Http\Controllers\AchievementController::class, 'index'])->name('achievements.index');
    
               // Leaderboard
    Route::get('/leaderboards', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboards.index');
    
        // Analytics
    Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');

    // Rewards
    Route::post('/rewards/daily', [\App\Http\Controllers\RewardController::class, 'claimDaily'])->name('rewards.daily');
    Route::post('/rewards/mystery-box/open', [\App\Http\Controllers\RewardController::class, 'openMysteryBox'])->name('rewards.mystery-box.open');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::post('/notifications/clear-all', [\App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clearAll');
});

require __DIR__.'/auth.php';
