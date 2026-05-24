<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MysteryBox extends Model
{
    protected $fillable = [
        'type',
        'name',
        'description',
        'rarity',
        'glow_color',
        'min_coins',
        'max_coins',
        'min_diamonds',
        'max_diamonds',
        'grants_avatar',
        'grants_border',
        'availability',
        'shop_price_coins',
        'shop_price_diamonds',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_coins' => 'integer',
            'max_coins' => 'integer',
            'min_diamonds' => 'integer',
            'max_diamonds' => 'integer',
            'grants_avatar' => 'boolean',
            'grants_border' => 'boolean',
            'is_active' => 'boolean',
            'shop_price_coins' => 'integer',
            'shop_price_diamonds' => 'integer',
        ];
    }

    public function userBoxes(): HasMany
    {
        return $this->hasMany(UserBox::class, 'mystery_box_id');
    }

    /**
     * Generate randomized reward within this box type's bounds.
     * All RNG happens server-side — never trust frontend.
     */
    public function generateReward(): array
    {
        $reward = [
            'coins' => rand($this->min_coins, $this->max_coins),
            'diamonds' => rand($this->min_diamonds, $this->max_diamonds),
            'items' => [],
        ];

        // Mythic boxes can grant exclusive cosmetics
        if ($this->grants_avatar && rand(1, 100) <= 40) {
            $reward['items'][] = [
                'type' => 'avatar',
                'key' => 'mythic_avatar_' . rand(1, 5),
                'name' => 'Mythic Avatar #' . rand(1, 5),
            ];
        }

        if ($this->grants_border && rand(1, 100) <= 30) {
            $reward['items'][] = [
                'type' => 'border',
                'key' => 'legendary_border_' . rand(1, 3),
                'name' => 'Legendary Border #' . rand(1, 3),
            ];
        }

        return $reward;
    }

    /**
     * Scope: only available boxes (not event/seasonal-only when no event)
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
            ->where('availability', 'always');
    }
}
