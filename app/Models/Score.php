<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = ['user_id', 'game_id', 'score', 'time_taken', 'difficulty', 'xp_earned', 'coins_earned'];

    public function user() { return $this->belongsTo(User::class); }
    public function game() { return $this->belongsTo(Game::class); }
}
