<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialContractApproval extends Model
{
    protected $fillable = [
        'social_contract_record_id',
        'student_id',
        'student_name',
        'event_name',
        'organization',
        'venue',
        'hours_rendered',
        'date',
        'status',
        'rejection_reason',
        'verified_by',
        'approved_by',
        'verified_at',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationship with SocialContractRecord
     */
    public function socialContractRecord()
    {
        return $this->belongsTo(SocialContractRecord::class);
    }

    /**
     * Relationship with AdminUser who verified
     */
    public function verifiedBy()
    {
        return $this->belongsTo(AdminUser::class, 'verified_by');
    }

    /**
     * Relationship with SuperAdmin who approved/rejected
     */
    public function approvedBy()
    {
        return $this->belongsTo(SuperAdmin::class, 'approved_by');
    }

    /**
     * Scope to get only verified records (for approval)
     */
    public function scopeForApproval($query)
    {
        return $query->where('status', 'Verified');
    }

    /**
     * Scope to get approved records
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    /**
     * Scope to get rejected records
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }
}
