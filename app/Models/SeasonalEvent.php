<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeasonalEvent extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'image',
        'rewards',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rewards' => 'array',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope: only active events.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    /**
     * Check if the event is currently running.
     */
    public function isRunning(): bool
    {
        return $this->is_active && $this->starts_at->isPast() && $this->ends_at->isFuture();
    }
}
