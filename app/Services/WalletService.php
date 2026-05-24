<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WalletService
{
    /**
     * Credit currency to a user's wallet.
     */
    public function credit(int $userId, string $currency, int $amount, string $source, $reference = null, string $description = null): Wallet
    {
        if ($amount < 0) {
            throw new InvalidArgumentException("Amount must be positive.");
        }
        if (!in_array($currency, ['coins', 'diamonds'])) {
            throw new InvalidArgumentException("Invalid currency type: {$currency}.");
        }

        return DB::transaction(function () use ($userId, $currency, $amount, $source, $reference, $description) {
            $wallet = Wallet::firstOrCreate(['user_id' => $userId]);
            
            // Increment wallet balance
            $wallet->increment($currency, $amount);
            $wallet->refresh();

            // Sync with User table for backward compatibility
            $user = User::find($userId);
            if ($user) {
                $user->increment($currency, $amount);
            }

            // Log Transaction
            WalletTransaction::create([
                'user_id' => $userId,
                'type' => 'credit',
                'currency' => $currency,
                'amount' => $amount,
                'balance_after' => $wallet->{$currency},
                'source' => $source,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
                'description' => $description,
            ]);

            return $wallet;
        });
    }

    /**
     * Debit currency from a user's wallet.
     */
    public function debit(int $userId, string $currency, int $amount, string $source, $reference = null, string $description = null): Wallet
    {
        if ($amount < 0) {
            throw new InvalidArgumentException("Amount must be positive.");
        }
        if (!in_array($currency, ['coins', 'diamonds'])) {
            throw new InvalidArgumentException("Invalid currency type: {$currency}.");
        }

        return DB::transaction(function () use ($userId, $currency, $amount, $source, $reference, $description) {
            $wallet = Wallet::firstOrCreate(['user_id' => $userId]);

            if ($wallet->{$currency} < $amount) {
                throw new \Exception("Insufficient balance in {$currency}. Required: {$amount}, Available: {$wallet->{$currency}}");
            }

            // Decrement wallet balance
            $wallet->decrement($currency, $amount);
            $wallet->refresh();

            // Sync with User table for backward compatibility
            $user = User::find($userId);
            if ($user) {
                $user->decrement($currency, $amount);
            }

            // Log Transaction
            WalletTransaction::create([
                'user_id' => $userId,
                'type' => 'debit',
                'currency' => $currency,
                'amount' => $amount,
                'balance_after' => $wallet->{$currency},
                'source' => $source,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
                'description' => $description,
            ]);

            return $wallet;
        });
    }

    /**
     * Get wallet balance for a user.
     */
    public function getBalance(int $userId): array
    {
        $wallet = Wallet::firstOrCreate(['user_id' => $userId]);
        return [
            'coins' => $wallet->coins,
            'diamonds' => $wallet->diamonds,
        ];
    }
}
