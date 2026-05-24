<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'shop_item_id',
        'item_name',
        'payment_method',
        'payment_currency',
        'amount_paid',
        'gateway_order_id',
        'gateway_payment_id',
        'gateway_signature',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount_paid' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shopItem(): BelongsTo
    {
        return $this->belongsTo(ShopItem::class);
    }

    /**
     * Scope: completed purchases only
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: pending purchases
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Mark purchase as completed.
     */
    public function markCompleted(string $paymentId = null, string $signature = null): void
    {
        $this->update([
            'status' => 'completed',
            'gateway_payment_id' => $paymentId ?? $this->gateway_payment_id,
            'gateway_signature' => $signature ?? $this->gateway_signature,
        ]);
    }

    /**
     * Mark purchase as failed.
     */
    public function markFailed(): void
    {
        $this->update(['status' => 'failed']);
    }
}
