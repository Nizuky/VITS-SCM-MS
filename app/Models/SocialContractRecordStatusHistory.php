<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialContractRecordStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_contract_record_id',
        'old_status',
        'new_status',
        'changed_by',
        'changed_at',
        'change_reason',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /**
     * Get the social contract record whose status changed.
     */
    public function socialContractRecord(): BelongsTo
    {
        return $this->belongsTo(SocialContractRecord::class);
    }

    /**
     * Get the admin user who changed the status.
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'changed_by');
    }
}
