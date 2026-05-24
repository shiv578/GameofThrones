<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LoginStreak extends Model
{
    protected $fillable = [
        'user_id',
        'streak_count',
        'longest_streak',
        'last_claim_date',
        'claimed_today',
        'total_claims',
    ];

    protected function casts(): array
    {
        return [
            'streak_count' => 'integer',
            'longest_streak' => 'integer',
            'last_claim_date' => 'date',
            'claimed_today' => 'boolean',
            'total_claims' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the user can claim today's reward.
     */
    public function canClaimToday(): bool
    {
        if ($this->claimed_today) return false;
        if (!$this->last_claim_date) return true;
        return !$this->last_claim_date->isToday();
    }

    /**
     * Check if streak should be reset (missed a day).
     */
    public function isStreakBroken(): bool
    {
        if (!$this->last_claim_date) return false;
        return $this->last_claim_date->lt(Carbon::yesterday()->startOfDay());
    }

    /**
     * Get the current day number in the 7-day cycle.
     */
    public function getCurrentDayNumber(): int
    {
        return (($this->streak_count) % 7) + 1;
    }
}
