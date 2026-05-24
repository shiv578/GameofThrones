<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'image',
        'rarity',
        'price_coins',
        'price_diamonds',
        'price_inr',
        'price_usd',
        'discount_percent',
        'original_price_coins',
        'original_price_diamonds',
        'is_limited',
        'stock',
        'available_from',
        'expires_at',
        'item_data',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price_coins' => 'integer',
            'price_diamonds' => 'integer',
            'price_inr' => 'integer',
            'price_usd' => 'integer',
            'discount_percent' => 'integer',
            'original_price_coins' => 'integer',
            'original_price_diamonds' => 'integer',
            'is_limited' => 'boolean',
            'stock' => 'integer',
            'available_from' => 'datetime',
            'expires_at' => 'datetime',
            'item_data' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(UserPurchase::class);
    }

    /**
     * Scope: active items only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: currently available (within date range)
     */
    public function scopeCurrentlyAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('available_from')
                    ->orWhere('available_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope: limited-time offers
     */
    public function scopeLimitedOffers($query)
    {
        return $query->currentlyAvailable()
            ->where('is_limited', true)
            ->whereNotNull('expires_at');
    }

    /**
     * Scope: by category
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Check if item is in stock.
     */
    public function isInStock(): bool
    {
        if (!$this->is_limited || $this->stock === null) return true;
        return $this->stock > 0;
    }

    /**
     * Check if item has an active discount.
     */
    public function hasDiscount(): bool
    {
        return $this->discount_percent && $this->discount_percent > 0;
    }

    /**
     * Get formatted INR price.
     */
    public function getFormattedPriceInrAttribute(): ?string
    {
        if (!$this->price_inr) return null;
        return '₹' . number_format($this->price_inr / 100, 0);
    }

    /**
     * Get formatted USD price.
     */
    public function getFormattedPriceUsdAttribute(): ?string
    {
        if (!$this->price_usd) return null;
        return '$' . number_format($this->price_usd / 100, 2);
    }
}
