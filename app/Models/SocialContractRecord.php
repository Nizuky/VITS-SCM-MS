<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SocialContractRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_contract_id',
        'date',
        'event_name',
        'venue',
        'organization',
        'supervisor_name',
        'hours_rendered',
        'status',
        'rejection_reason',
        'rejected_at',
    ];

    protected $casts = [
        'date' => 'date',
        'hours_rendered' => 'integer',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the social contract that owns this record.
     */
    public function socialContract(): BelongsTo
    {
        return $this->belongsTo(SocialContract::class);
    }

    /**
     * Get the approval record for this social contract record.
     */
    public function approval(): HasOne
    {
        return $this->hasOne(SocialContractApproval::class);
    }

    /**
     * Check if the record is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Check if the record is verified.
     */
    public function isVerified(): bool
    {
        return $this->status === 'Verified';
    }

    /**
     * Check if the record is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    /**
     * Check if the record is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }

    /**
     * Get the student user who owns this record through social contract
     */
    public function getUserIdAttribute()
    {
        return $this->socialContract->student_id ?? null;
    }
}
