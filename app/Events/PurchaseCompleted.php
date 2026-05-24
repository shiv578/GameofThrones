<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\UserPurchase;

class PurchaseCompleted
{
    use Dispatchable, SerializesModels;

    public UserPurchase $purchase;

    public function __construct(UserPurchase $purchase)
    {
        $this->purchase = $purchase;
    }
}
