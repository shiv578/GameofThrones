<?php

namespace App\Http\Controllers;

use App\Models\ShopItem;
use App\Services\ShopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    protected ShopService $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    /**
     * Display the in-game shop catalog.
     */
    public function index(Request $request): View
    {
        $category = $request->query('category', 'all');

        $query = ShopItem::currentlyAvailable();

        if ($category !== 'all') {
            $query->inCategory($category);
        }

        $items = $query->orderBy('sort_order', 'asc')->get();

        // Also fetch active offers (discounted or limited-time items)
        $offers = ShopItem::limitedOffers()->get();

        return view('shop.index', compact('items', 'offers', 'category'));
    }

    /**
     * Purchase a shop item.
     */
    public function purchase(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'currency' => ['required', 'string', 'in:coins,diamonds'],
        ]);

        try {
            $currency = $request->input('currency');
            $result = $this->shopService->purchaseItem($request->user()->id, $id, $currency);
            
            return response()->json([
                'success' => true,
                'message' => "🛒 Purchase completed! {$result['item_name']} delivered.",
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
