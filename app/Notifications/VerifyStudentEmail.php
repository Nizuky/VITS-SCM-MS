<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class VerifyStudentEmail extends Notification
{
    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify your PLV email address')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Please confirm your identity for the VITS Social Contract Monitoring and Management System.')
            ->line('Full name: ' . $notifiable->name)
            ->line('Student ID: ' . ($notifiable->student_id ?? ''))
            ->action('Confirm my email', $verificationUrl)
            ->line('After confirming, you will be redirected to your student dashboard.');
    }

    /**
     * Create a signed verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable): string
    {
        $temporarySignedRouteName = 'verification.verify';

        $parameters = [
            'id' => $notifiable->getKey(),
            'hash' => sha1($notifiable->getEmailForVerification()),
        ];

        return URL::temporarySignedRoute($temporarySignedRouteName, Carbon::now()->addMinutes(config('auth.verification.expire', 60)), $parameters);
    }
}
