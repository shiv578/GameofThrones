<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'type', 'title', 'message', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    protected $appends = ['icon', 'time_ago', 'is_read'];

    public function user() { return $this->belongsTo(User::class); }

    /**
     * Get themed icon class based on notification type.
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'achievement' => 'fa-trophy text-yellow-400',
            'level_up'    => 'fa-bolt text-purple-400',
            'reminder'    => 'fa-castle text-amber-400',
            'reward'      => 'fa-gift text-red-400',
            'tip'         => 'fa-lightbulb text-cyan-400',
            'streak'      => 'fa-fire-flame-curved text-orange-400',
            'challenge'   => 'fa-chart-line text-emerald-400',
            'milestone'   => 'fa-medal text-yellow-300',
            'game'        => 'fa-gamepad text-blue-400',
            default       => 'fa-bell text-gray-400',
        };
    }

    /**
     * Human-readable time ago.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if notification is read.
     */
    public function getIsReadAttribute(): bool
    {
        return $this->read_at !== null;
    }
}
