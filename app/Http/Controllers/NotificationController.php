<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Obtenir les notifications récentes pour l'utilisateur connecté
     */
    public function getRecent()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $notifications = $this->notificationService->getRecentNotifications($user, 5);
        $unreadCount = $this->notificationService->getUnreadCount($user);

        return response()->json([
            'notifications' => $notifications->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'time_ago' => $notification->time_ago,
                    'is_read' => $notification->isRead(),
                    'data' => $notification->data
                ];
            }),
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['success' => false], 403);
        }

        $notification = \App\Models\Notifications::where('id', $id)
            ->where('member_id', $user->id)
            ->first();

        if (!$notification) {
            return response()->json(['success' => false], 404);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['success' => false], 403);
        }

        $this->notificationService->markAllAsRead($user);

        return response()->json(['success' => true]);
    }

    /**
     * Obtenir le nombre de notifications non lues
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['count' => 0]);
        }

        $count = $this->notificationService->getUnreadCount($user);

        return response()->json(['count' => $count]);
    }
}