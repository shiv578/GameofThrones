<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Send a notification to a user.
     */
    public function send(int $userId, string $type, array $data): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $data['title'] ?? 'New Notification',
            'message' => $data['message'] ?? '',
            'read_at' => null,
        ]);
    }

    /**
     * Get unread notifications for a user.
     */
    public function getUnread(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all notifications for a user.
     */
    public function getAll(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(int $userId, int $notificationId): bool
    {
        $notification = Notification::where('user_id', $userId)
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            return $notification->update(['read_at' => now()]);
        }

        return false;
    }

    /**
     * Mark all notifications for a user as read.
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
