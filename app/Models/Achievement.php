<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'xp_reward',
        'coin_reward',
        'diamond_reward',
        'requirement_type',
        'requirement_value',
        'progress_target',
        'category',
        'rarity',
        'badge_icon',
        'badge_color',
        'is_claimable',
        'is_active',
        'sort_order'
    ];

    public function users() { return $this->belongsToMany(User::class, 'user_achievements')->withPivot('unlocked_at'); }
    
    public function progress() { return $this->hasMany(AchievementProgress::class); }
}
