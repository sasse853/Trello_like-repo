<?php

namespace App\Services;

use App\Models\Notifications; // Modèle Notifications
use App\Models\Users;
use App\Events\NotificationCreated;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Créer une nouvelle notification
     */
    public function create(array $data): Notifications
    {
        $notification = Notifications::create([
            'type'            => $data['type'],
            'member_id'       => $data['member_id'],
            'notifiable_type' => $data['notifiable_type'] ?? null,
            'notifiable_id'   => $data['notifiable_id'] ?? null,
            'data'            => $data['data']
        ]);

        // Déclencher l'event pour les notifications en temps réel
        event(new NotificationCreated($notification));

        return $notification;
    }

    /**
     * Créer notification d'invitation au projet
     */
    public function createBoardInvitation(Users $member, $board): void
    {
        $this->create([
            'type'            => 'board_invitation',
            'member_id'       => $member->id,
            'notifiable_type' => get_class($board),
            'notifiable_id'   => $board->id,
            'data'            => [
                'board_name' => $board->name,
                'board_id'   => $board->id,
                'invited_by' => Auth::user()?->name ?? 'Système'
            ]
        ]);
    }

    /**
     * Créer notification de mise à jour de liste
     */
    public function createListUpdate(Users $member, $list): void
    {
        $this->create([
            'type'            => 'list_updated',
            'member_id'       => $member->id,
            'notifiable_type' => get_class($list),
            'notifiable_id'   => $list->id,
            'data'            => [
                'list_name'  => $list->name,
                'list_id'    => $list->id,
                'updated_by' => Auth::user()?->name ?? 'Système'
            ]
        ]);
    }

    /**
     * Créer notification d'assignation de tâche
     */
    public function createTaskAssignment(Users $member, $task): void
    {
        $this->create([
            'type'            => 'task_assigned',
            'member_id'       => $member->id,
            'notifiable_type' => get_class($task),
            'notifiable_id'   => $task->id,
            'data'            => [
                'task_title'  => $task->title,
                'task_id'     => $task->id,
                'assigned_by' => Auth::user()?->name ?? 'Système'
            ]
        ]);
    }

    /**
     * Obtenir les notifications récentes pour un membre
     */
    public function getRecentNotifications(Users $member, int $limit = 5)
    {
        return Notifications::where('member_id', $member->id)
            ->with('notifiable')
            ->recent($limit) // Doit être défini comme scope dans le modèle
            ->get();
    }

    /**
     * Compter les notifications non lues
     */
    public function getUnreadCount(Users $member): int
    {
        return Notifications::where('member_id', $member->id)
            ->unread() // Doit être défini comme scope dans le modèle
            ->count();
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead(Users $member): void
    {
        Notifications::where('member_id', $member->id)
            ->unread() // Doit être défini comme scope dans le modèle
            ->update(['read_at' => now()]);
    }
}
