<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoginStreakUpdated
{
    use Dispatchable, SerializesModels;

    public int $userId;
    public int $streakCount;

    public function __construct(int $userId, int $streakCount)
    {
        $this->userId = $userId;
        $this->streakCount = $streakCount;
    }
}
