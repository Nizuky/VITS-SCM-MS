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
        'rejected_at',
    ];

    protected $casts = [
        'date' => 'date',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Relationship with SocialContractRecord
     */
    public function socialContractRecord()
    {
        return $this->belongsTo(SocialContractRecord::class);
    }

    /**
     * Relationship with the User (student)
     * student_id in approvals table is stored as string format like "23-3495"
     * but in users table it's the actual ID, so we need to get it through the record
     */
    public function student()
    {
        return $this->hasOneThrough(
            User::class,
            SocialContractRecord::class,
            'id', // Foreign key on social_contract_records table
            'id', // Foreign key on users table  
            'social_contract_record_id', // Local key on approvals table
            'social_contract_id' // Local key on social_contract_records table
        )->join('social_contracts', 'social_contracts.id', '=', 'social_contract_records.social_contract_id')
         ->where('social_contracts.student_id', '=', \DB::raw('users.id'));
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
