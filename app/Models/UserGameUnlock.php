<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGameUnlock extends Model
{
    protected $fillable = [
        'user_id',
        'premium_game_id',
        'unlock_method',
        'purchase_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function premiumGame(): BelongsTo
    {
        return $this->belongsTo(PremiumGame::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(UserPurchase::class, 'purchase_id');
    }
}
