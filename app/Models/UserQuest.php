<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuest extends Model
{
    protected $fillable = [
        'user_id',
        'daily_quest_id',
        'current_progress',
        'is_completed',
        'is_claimed',
        'assigned_date',
        'completed_at',
        'claimed_at',
    ];

    protected function casts(): array
    {
        return [
            'current_progress' => 'integer',
            'is_completed' => 'boolean',
            'is_claimed' => 'boolean',
            'assigned_date' => 'date',
            'completed_at' => 'datetime',
            'claimed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dailyQuest(): BelongsTo
    {
        return $this->belongsTo(DailyQuest::class);
    }

    /**
     * Scope: quests assigned today.
     */
    public function scopeAssignedToday($query)
    {
        return $query->whereDate('assigned_date', now()->toDateString());
    }

    /**
     * Check if the quest can be claimed.
     */
    public function canClaim(): bool
    {
        return $this->is_completed && !$this->is_claimed;
    }

    /**
     * Get progress as a percentage.
     */
    public function getProgressPercentAttribute(): int
    {
        $target = $this->dailyQuest->requirement_value ?? 1;
        if ($target <= 0) $target = 1;
        return min(100, intval(($this->current_progress / $target) * 100));
    }

    /**
     * Increment progress and auto-complete if requirement is met.
     */
    public function incrementProgress(int $amount = 1): void
    {
        if ($this->is_completed) return;

        $this->increment('current_progress', $amount);
        $this->refresh();

        $target = $this->dailyQuest->requirement_value ?? 1;
        if ($this->current_progress >= $target) {
            $this->update([
                'is_completed' => true,
                'completed_at' => now(),
            ]);
        }
    }
}
