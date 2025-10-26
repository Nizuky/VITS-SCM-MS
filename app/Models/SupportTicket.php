<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    protected $fillable = [
        'student_id',
        'student_name',
        'issue_type',
        'details',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the student that owns the ticket.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Check if student can submit a ticket today.
     */
    public static function canSubmitTicket(int $studentId): bool
    {
        $today = now()->startOfDay();
        $ticketsToday = self::where('student_id', $studentId)
            ->whereDate('created_at', $today)
            ->count();
        
        return $ticketsToday < 2;
    }

    /**
     * Get remaining tickets for today.
     */
    public static function getRemainingTickets(int $studentId): int
    {
        $today = now()->startOfDay();
        $ticketsToday = self::where('student_id', $studentId)
            ->whereDate('created_at', $today)
            ->count();
        
        return max(0, 2 - $ticketsToday);
    }
}
