<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AchievementProgress extends Model
{
    protected $table = 'achievement_progress';

    protected $fillable = [
        'user_id',
        'achievement_id',
        'current_progress',
        'is_completed',
        'is_claimed',
        'completed_at',
        'claimed_at',
    ];

    protected function casts(): array
    {
        return [
            'current_progress' => 'integer',
            'is_completed' => 'boolean',
            'is_claimed' => 'boolean',
            'completed_at' => 'datetime',
            'claimed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    /**
     * Get progress as percentage (0-100).
     */
    public function getProgressPercentAttribute(): int
    {
        $target = $this->achievement->progress_target ?? 1;
        if ($target <= 0) $target = 1;
        return min(100, intval(($this->current_progress / $target) * 100));
    }

    /**
     * Check if reward can be claimed.
     */
    public function canClaim(): bool
    {
        return $this->is_completed && !$this->is_claimed;
    }

    /**
     * Increment progress and auto-complete if threshold reached.
     */
    public function incrementProgress(int $amount = 1): void
    {
        $this->increment('current_progress', $amount);
        $this->refresh();

        $target = $this->achievement->progress_target ?? 1;
        if ($this->current_progress >= $target && !$this->is_completed) {
            $this->update([
                'is_completed' => true,
                'completed_at' => now(),
            ]);
        }
    }
}
