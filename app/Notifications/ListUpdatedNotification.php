<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ListUpdatedNotification extends Notification
{
    use Queueable;
    public $list;

    public function __construct($list)
    {
        $this->list = $list;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mise à jour de votre liste')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line("Votre liste '{$this->list->name}' a été modifiée.")
            ->action('Voir les modifications', url('/boards/' . $this->list->board_id))
            ->line('Merci de votre participation !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
