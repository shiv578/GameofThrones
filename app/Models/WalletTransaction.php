<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'currency',
        'amount',
        'balance_after',
        'source',
        'reference_type',
        'reference_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'balance_after' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic reference to the source entity (e.g., Score, Achievement, UserBox).
     */
    public function reference()
    {
        return $this->morphTo('reference');
    }

    /**
     * Scope: only credits
     */
    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    /**
     * Scope: only debits
     */
    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    /**
     * Scope: filter by currency
     */
    public function scopeForCurrency($query, string $currency)
    {
        return $query->where('currency', $currency);
    }
}
