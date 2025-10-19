<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfilePasswordReset extends Notification
{
    use Queueable;

    public function __construct(public string $resetUrl)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirm profile change by resetting your password')
            ->greeting('Hello '.$notifiable->name)
            ->line('You requested to change your profile. Please confirm by resetting your password using the button below.')
            ->action('Reset Password', $this->resetUrl)
            ->line('If you did not request this, no further action is required.');
    }
}
