<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get list of unread and recent notifications.
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $unread = $this->notificationService->getUnread($userId);
        $all = $this->notificationService->getAll($userId)->take(20);

        return response()->json([
            'success' => true,
            'unread_count' => $unread->count(),
            'unread' => $unread,
            'recent' => $all,
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function read(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;
        $success = $this->notificationService->markAsRead($userId, $id);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found.',
        ], 404);
    }

    /**
     * Mark all notifications as read.
     */
    public function readAll(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $count = $this->notificationService->markAllAsRead($userId);

        return response()->json([
            'success' => true,
            'message' => "Marked {$count} notifications as read.",
        ]);
    }

    /**
     * Clear (delete) all notifications.
     */
    public function clearAll(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $count = $this->notificationService->clearAll($userId);

        return response()->json([
            'success' => true,
            'message' => "Cleared {$count} notifications.",
        ]);
    }
}
