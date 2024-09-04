<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CallbackRequested extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $data)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Demande de rappel')
                    ->line('Une nouvelle demande de rappel a été effectuée.')
                    ->line('Nom: ' . $this->data['name'])
                    ->line('Téléphone: ' . $this->data['phone'])
                    ->line('Heure préférée: ' . $this->data['preferredTime']);
    }
}
