<?php

namespace App\Listeners;

use App\Events\ListUpdated;
use App\Notifications\ListUpdatedNotification;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendListUpdatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(ListUpdated $event): void
    {
        $list = $event->list;

        // Trouver le membre concerné (la liste porte son nom)
        $user = \App\Models\Users::where('name', $list->name)->first();

        if ($user && $user->email) {
            $user->notify(new ListUpdatedNotification($list));
        }
    }
}
