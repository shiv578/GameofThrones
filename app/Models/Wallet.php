<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'coins',
        'diamonds',
    ];

    protected function casts(): array
    {
        return [
            'coins' => 'integer',
            'diamonds' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'user_id', 'user_id');
    }

    /**
     * Check if wallet has sufficient balance for a given currency.
     */
    public function hasSufficientBalance(string $currency, int $amount): bool
    {
        return $this->{$currency} >= $amount;
    }
}
