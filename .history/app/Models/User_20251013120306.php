<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'student_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factory_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Normalize student_id when set. Accept 6-digit number or already dashed format.
     * Examples: "233402" -> "23-3402"; "23-3402" -> "23-3402"
     */
    public function setStudentIdAttribute(?string $value): void
    {
        if (empty($value)) {
            $this->attributes['student_id'] = null;
            return;
        }

        $clean = preg_replace('/[^0-9]/', '', $value);
        if (strlen($clean) === 6) {
            $this->attributes['student_id'] = substr($clean, 0, 2) . '-' . substr($clean, 2);
        } else {
            $this->attributes['student_id'] = $value; // leave as-is
        }
    }

    /**
     * Send the email verification notification using our custom notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\VerifyStudentEmail());
    }
}
