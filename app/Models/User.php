<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'house',
        'character_class',
        'xp',
        'coins',
        'diamonds',
        'level',
        'rank',
        'total_login_days',
        'last_login_date',
        'login_streak',
        'current_streak',
        'avatar_border',
        'equipped_theme',
        'theme_preference',
        'last_reward_claimed_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_date' => 'date',
            'xp' => 'integer',
            'coins' => 'integer',
            'diamonds' => 'integer',
            'level' => 'integer',
            'total_login_days' => 'integer',
            'login_streak' => 'integer',
            'current_streak' => 'integer',
            'last_reward_claimed_at' => 'datetime',
        ];
    }

    public function scores() { return $this->hasMany(Score::class); }
    public function achievements() { return $this->belongsToMany(Achievement::class, 'user_achievements')->withPivot('unlocked_at'); }
    public function leaderboard() { return $this->hasOne(Leaderboard::class); }
    public function settings() { return $this->hasOne(Setting::class); }
    public function notifications() { return $this->hasMany(Notification::class); }
    public function gameSessions() { return $this->hasMany(GameSession::class); }
    public function activityLogs() { return $this->hasMany(ActivityLog::class); }

    // New relationships
    public function wallet() { return $this->hasOne(Wallet::class); }
    public function walletTransactions() { return $this->hasMany(WalletTransaction::class); }
    public function loginStreak() { return $this->hasOne(LoginStreak::class); }
    public function boxes() { return $this->hasMany(UserBox::class); }
    public function inventories() { return $this->hasMany(UserInventory::class); }
    public function purchases() { return $this->hasMany(UserPurchase::class); }
    public function quests() { return $this->hasMany(UserQuest::class); }
    public function achievementProgress() { return $this->hasMany(AchievementProgress::class); }
    public function gameUnlocks() { return $this->hasMany(UserGameUnlock::class); }
}
