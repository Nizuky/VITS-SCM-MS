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
     * A student can have many social contracts
     */
    public function socialContracts()
    {
        return $this->hasMany(\App\Models\SocialContract::class, 'student_id');
    }

    /**
     * Helper to get or create the current social contract for this student.
     * For now, returns the most recent contract by creation date or creates one if none exists.
     */
    public function currentSocialContract(): \App\Models\SocialContract
    {
        $contract = $this->socialContracts()->latest('id')->first();
        if (!$contract) {
            $contract = $this->socialContracts()->create([
                'status' => 'submitted',
                'submission_date' => now(),
            ]);
        }
        return $contract;
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
        try {
            $this->notify(new \App\Notifications\VerifyStudentEmail());
            return;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('VerifyStudentEmail notification failed', ['user_id' => $this->getKey(), 'error' => $e->getMessage()]);
        }

        // Fallback: generate verification URL and send a simple mailable. If this fails, log and rethrow
        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            \Illuminate\Support\Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );

        try {
            \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\StudentVerifyMail($verificationUrl, $this));
        } catch (\Throwable $ex) {
            \Illuminate\Support\Facades\Log::error('StudentVerifyMail send failed', ['user_id' => $this->getKey(), 'error' => $ex->getMessage()]);
            throw $ex;
        }
    }
}
