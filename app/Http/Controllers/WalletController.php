<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Display wallet balance details and transaction history.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $balance = $this->walletService->getBalance($user->id);

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('wallet.index', compact('balance', 'transactions'));
    }

    /**
     * Get JSON wallet balances API.
     */
    public function balance(Request $request): JsonResponse
    {
        $balance = $this->walletService->getBalance($request->user()->id);
        return response()->json([
            'success' => true,
            'coins' => $balance['coins'],
            'diamonds' => $balance['diamonds'],
        ]);
    }
}
