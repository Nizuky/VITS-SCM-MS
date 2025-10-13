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
        // Use the default notification which expects a url property, or dispatch a Mailable
        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
        // Note: the default notification builds the URL from route('password.reset', $token)
        // To ensure admin route is used, you can swap in a custom notification if needed.
    }
}
