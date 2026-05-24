<?php

namespace App\Http\Controllers;

use App\Models\UserBox;
use App\Models\MysteryBox;
use App\Services\MysteryBoxService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MysteryBoxController extends Controller
{
    protected MysteryBoxService $mysteryBoxService;

    public function __construct(MysteryBoxService $mysteryBoxService)
    {
        $this->mysteryBoxService = $mysteryBoxService;
    }

    /**
     * Display the user's mystery boxes inventory.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Unopened boxes owned by the user
        $unopenedBoxes = UserBox::with('mysteryBox')
            ->where('user_id', $user->id)
            ->unopened()
            ->get();

        // Available box templates in the catalog
        $catalogBoxes = MysteryBox::where('is_active', true)->get();

        return view('mystery-boxes.index', compact('unopenedBoxes', 'catalogBoxes'));
    }

    /**
     * Open a mystery box.
     */
    public function open(Request $request, int $id): JsonResponse
    {
        try {
            $result = $this->mysteryBoxService->openBox($request->user()->id, $id);
            return response()->json([
                'success' => true,
                'message' => "✨ Box opened successfully!",
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
