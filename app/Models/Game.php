<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['slug', 'name', 'description', 'category', 'difficulty', 'image', 'max_score'];

    public function scores() { return $this->hasMany(Score::class); }
    public function sessions() { return $this->hasMany(GameSession::class); }
}
