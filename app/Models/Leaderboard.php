<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    protected $fillable = ['user_id', 'total_xp', 'total_coins', 'games_played', 'highest_score', 'win_count', 'rank', 'period'];

    public function user() { return $this->belongsTo(User::class); }
}
