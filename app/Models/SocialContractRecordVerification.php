<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialContractRecordVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_contract_record_id',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * Get the social contract record that was verified.
     */
    public function socialContractRecord(): BelongsTo
    {
        return $this->belongsTo(SocialContractRecord::class);
    }

    /**
     * Get the admin user who verified the record.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'verified_by');
    }
}
