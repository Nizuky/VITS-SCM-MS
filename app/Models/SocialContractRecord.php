<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function socialContract(): BelongsTo
    {
        return $this->belongsTo(SocialContract::class);
    }
}
