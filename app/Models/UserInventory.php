<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInventory extends Model
{
    protected $table = 'user_inventories';

    protected $fillable = [
        'user_id',
        'item_type',
        'item_key',
        'item_name',
        'item_data',
        'source',
        'source_id',
        'is_equipped',
    ];

    protected function casts(): array
    {
        return [
            'item_data' => 'array',
            'is_equipped' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: filter by item type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('item_type', $type);
    }

    /**
     * Scope: currently equipped items
     */
    public function scopeEquipped($query)
    {
        return $query->where('is_equipped', true);
    }

    /**
     * Equip this item, unequipping other items of the same type for this user.
     */
    public function equip(): void
    {
        // Unequip other items of the same type
        static::where('user_id', $this->user_id)
            ->where('item_type', $this->item_type)
            ->where('id', '!=', $this->id)
            ->update(['is_equipped' => false]);

        $this->update(['is_equipped' => true]);
    }
}
