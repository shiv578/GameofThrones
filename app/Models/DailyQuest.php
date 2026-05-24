<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyQuest extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'requirement_type',
        'requirement_value',
        'reward_coins',
        'reward_diamonds',
        'reward_xp',
        'icon',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requirement_value' => 'integer',
            'reward_coins' => 'integer',
            'reward_diamonds' => 'integer',
            'reward_xp' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function userQuests(): HasMany
    {
        return $this->hasMany(UserQuest::class);
    }

    /**
     * Scope: only active quests.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
