<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class BoardInvitationNotification extends Notification
{
    use Queueable;

    protected $board;
    protected $addedBy;


    public function __construct($addedBy, $board)
    {
        $addedBy=Auth::user();

        $this->board = $board;
        $this->addedBy = $addedBy;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail']; // Maintenant envoie par email et sauvegarde en base de données
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Invitation à rejoindre un tableau')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line("Vous avez été ajouté au projet '{$this->board->name}' par {$this->addedBy->name}.")
            ->action('Voir le projet', route('boards.showoff', ['board' => $this->board->id]));
            
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable)
    {
       //
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable)
    {
        //
    }
}