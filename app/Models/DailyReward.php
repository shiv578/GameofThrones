<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReward extends Model
{
    protected $fillable = [
        'day_number',
        'coins_reward',
        'diamonds_min',
        'diamonds_max',
        'diamond_chance',
        'box_type',
        'label',
        'icon',
    ];

    protected function casts(): array
    {
        return [
            'day_number' => 'integer',
            'coins_reward' => 'integer',
            'diamonds_min' => 'integer',
            'diamonds_max' => 'integer',
            'diamond_chance' => 'integer',
        ];
    }

    /**
     * Get the reward configuration for a specific day.
     */
    public static function forDay(int $dayNumber): ?self
    {
        // Cycle through 7 days
        $day = (($dayNumber - 1) % 7) + 1;
        return static::where('day_number', $day)->first();
    }

    /**
     * Calculate randomized diamond reward based on chance.
     */
    public function rollDiamonds(): int
    {
        if ($this->diamond_chance <= 0) return 0;
        if (rand(1, 100) > $this->diamond_chance) return 0;
        return rand($this->diamonds_min, $this->diamonds_max);
    }
}
