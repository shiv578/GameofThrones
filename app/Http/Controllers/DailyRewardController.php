<?php

namespace App\Http\Controllers;

use App\Services\DailyRewardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DailyRewardController extends Controller
{
    protected DailyRewardService $dailyRewardService;

    public function __construct(DailyRewardService $dailyRewardService)
    {
        $this->dailyRewardService = $dailyRewardService;
    }

    /**
     * Display the daily reward calendar.
     */
    public function index(Request $request): View
    {
        $state = $this->dailyRewardService->getCalendarState($request->user()->id);
        return view('rewards.daily', compact('state'));
    }

    /**
     * Claim today's daily reward.
     */
    public function claim(Request $request): JsonResponse
    {
        try {
            $result = $this->dailyRewardService->claimReward($request->user()->id);
            return response()->json([
                'success' => true,
                'message' => "🎁 Reward claimed successfully!",
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
