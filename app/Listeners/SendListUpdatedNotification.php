<?php

namespace App\Listeners;

use App\Events\ListUpdated;
use App\Notifications\ListUpdatedNotification;
use App\Services\NotificationService;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendListUpdatedNotification
{
    protected $notificationService;

    /**
     * Create the event listener.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(ListUpdated $event): void
    {
        $list = $event->list;

        // Trouver le membre concerné (la liste porte son nom)
        $user = \App\Models\Users::where('name', $list->name)->first();

        if ($user && $user->email && $user->member) {
            // Envoyer l'email (existant)
            $user->notify(new ListUpdatedNotification($list));
            
            // Créer la notification dans le dashboard
            $this->notificationService->createListUpdate($user->member, $list);
        }
    }
}