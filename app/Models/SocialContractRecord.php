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
        'hours_rendered',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'hours_rendered' => 'integer',
    ];

    /**
     * Get the social contract that owns this record.
     */
    public function socialContract(): BelongsTo
    {
        return $this->belongsTo(SocialContract::class);
    }

    /**
     * Get all verifications for this record.
     */
    public function verifications(): HasMany
    {
        return $this->hasMany(SocialContractRecordVerification::class);
    }

    /**
     * Get the latest verification for this record.
     */
    public function latestVerification(): HasOne
    {
        return $this->hasOne(SocialContractRecordVerification::class)->latestOfMany('verified_at');
    }

    /**
     * Get all rejections for this record.
     */
    public function rejections(): HasMany
    {
        return $this->hasMany(SocialContractRecordRejection::class);
    }

    /**
     * Get the latest rejection for this record.
     */
    public function latestRejection(): HasOne
    {
        return $this->hasOne(SocialContractRecordRejection::class)->latestOfMany('rejected_at');
    }

    /**
     * Get all approvals for this record.
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(SocialContractRecordApproval::class);
    }

    /**
     * Get the latest approval for this record.
     */
    public function latestApproval(): HasOne
    {
        return $this->hasOne(SocialContractRecordApproval::class)->latestOfMany('approved_at');
    }

    /**
     * Get the status history for this record.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(SocialContractRecordStatusHistory::class)->orderBy('changed_at', 'desc');
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
}
