<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialContractRecordRejection extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_contract_record_id',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'rejection_notes',
    ];

    protected $casts = [
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the social contract record that was rejected.
     */
    public function socialContractRecord(): BelongsTo
    {
        return $this->belongsTo(SocialContractRecord::class);
    }

    /**
     * Get the admin user who rejected the record.
     */
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'rejected_by');
    }
}
