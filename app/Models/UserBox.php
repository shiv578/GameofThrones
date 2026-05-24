<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBox extends Model
{
    protected $fillable = [
        'user_id',
        'mystery_box_id',
        'source',
        'is_opened',
        'opened_at',
        'reward_coins',
        'reward_diamonds',
        'reward_items',
    ];

    protected function casts(): array
    {
        return [
            'is_opened' => 'boolean',
            'opened_at' => 'datetime',
            'reward_coins' => 'integer',
            'reward_diamonds' => 'integer',
            'reward_items' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mysteryBox(): BelongsTo
    {
        return $this->belongsTo(MysteryBox::class);
    }

    /**
     * Scope: unopened boxes only
     */
    public function scopeUnopened($query)
    {
        return $query->where('is_opened', false);
    }

    /**
     * Scope: opened boxes only
     */
    public function scopeOpened($query)
    {
        return $query->where('is_opened', true);
    }

    /**
     * Check if this box can be opened.
     */
    public function canBeOpened(): bool
    {
        return !$this->is_opened;
    }
}
