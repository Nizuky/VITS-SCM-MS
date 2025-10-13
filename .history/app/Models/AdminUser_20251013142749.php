<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin_users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Send the password reset notification using the admin password broker.
     */
    public function sendPasswordResetNotification($token)
    {
        $url = url(route('admin.password.reset', ['token' => $token], false));
        // Send a mailable tailored for admin password reset so the admin route and branding are used.
        try {
            \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\AdminResetLinkMail($url));
        } catch (\Throwable $e) {
            // Fallback: if mail sending fails for some reason, fall back to the default notification implementation
            $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
        }
    }

    /**
     * Return the initials for the admin user's name (e.g. "JS" for "John Smith").
     * Falls back to the first letter of the email if name is missing.
     */
    public function initials(): string
    {
        $name = trim((string) ($this->name ?? ''));

        if ($name !== '') {
            // Split by whitespace and take up to the first two parts
            $parts = preg_split('/\s+/', $name);
            $first = isset($parts[0]) && $parts[0] !== '' ? mb_substr($parts[0], 0, 1) : '';
            $second = isset($parts[1]) && $parts[1] !== '' ? mb_substr($parts[1], 0, 1) : '';

            $initials = strtoupper($first . $second);

            // If there's only one name part, return a single initial
            return $initials !== '' ? $initials : strtoupper(mb_substr($name, 0, 1));
        }

        // Fallback to email first character
        $email = (string) ($this->email ?? '');
        return $email !== '' ? strtoupper(mb_substr($email, 0, 1)) : '';
    }
}
