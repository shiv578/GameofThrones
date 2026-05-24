<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Achievement;

class AchievementUnlocked
{
    use Dispatchable, SerializesModels;

    public int $userId;
    public Achievement $achievement;

    public function __construct(int $userId, Achievement $achievement)
    {
        $this->userId = $userId;
        $this->achievement = $achievement;
    }
}
