<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PremiumGame extends Model
{
    protected $fillable = [
        'game_id',
        'name',
        'description',
        'image',
        'price_diamonds',
        'price_inr',
        'price_usd',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_diamonds' => 'integer',
            'price_inr' => 'integer',
            'price_usd' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function unlocks(): HasMany
    {
        return $this->hasMany(UserGameUnlock::class);
    }

    /**
     * Check if a user has unlocked this premium game.
     */
    public function isUnlockedBy(int $userId): bool
    {
        return $this->unlocks()->where('user_id', $userId)->exists();
    }

    /**
     * Get formatted INR price.
     */
    public function getFormattedPriceInrAttribute(): string
    {
        return '₹' . number_format($this->price_inr / 100, 0);
    }

    /**
     * Get formatted USD price.
     */
    public function getFormattedPriceUsdAttribute(): string
    {
        return '$' . number_format($this->price_usd / 100, 2);
    }
}
