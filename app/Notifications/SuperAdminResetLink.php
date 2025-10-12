<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuperAdminResetLink extends Notification
{
    protected string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Super Admin Password Reset')
            ->line('A request has been made to reset the super admin password.')
            ->action('Reset Super Admin Password', $this->url)
            ->line('If you did not request this, please ignore this message.');
    }
}
