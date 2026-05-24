<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RewardClaimed
{
    use Dispatchable, SerializesModels;

    public int $userId;
    public string $source;
    public array $rewards;

    public function __construct(int $userId, string $source, array $rewards)
    {
        $this->userId = $userId;
        $this->source = $source;
        $this->rewards = $rewards;
    }
}
