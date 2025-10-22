<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialContractRecordApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_contract_record_id',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the social contract record that was approved.
     */
    public function socialContractRecord(): BelongsTo
    {
        return $this->belongsTo(SocialContractRecord::class);
    }

    /**
     * Get the admin user who approved the record.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'approved_by');
    }
}
