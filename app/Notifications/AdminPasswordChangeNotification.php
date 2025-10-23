<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class AdminPasswordChangeNotification extends Notification
{
    use Queueable;

    private $token;
    private $newPassword;

    public function __construct($token, $newPassword)
    {
        $this->token = $token;
        $this->newPassword = $newPassword;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'admin.password.verify',
            now()->addMinutes(60),
            [
                'token' => $this->token,
                'email' => $notifiable->email
            ]
        );

        return (new MailMessage)
            ->subject('Verify Your Password Change Request')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have requested to change your password.')
            ->line('Please click the button below to verify and confirm this password change.')
            ->action('Verify Password Change', $verificationUrl)
            ->line('This verification link will expire in 60 minutes.')
            ->line('If you did not request this password change, please ignore this email and your password will remain unchanged.')
            ->salutation('Regards, ' . config('app.name'));
    }
}
