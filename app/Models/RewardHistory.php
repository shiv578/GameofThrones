<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewardHistory extends Model
{
    protected $table = 'reward_history';

    protected $fillable = [
        'user_id',
        'source_type',
        'source_id',
        'coins_earned',
        'diamonds_earned',
        'xp_earned',
        'items_earned',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'coins_earned' => 'integer',
            'diamonds_earned' => 'integer',
            'xp_earned' => 'integer',
            'items_earned' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: filter by source type
     */
    public function scopeFromSource($query, string $sourceType)
    {
        return $query->where('source_type', $sourceType);
    }
}
