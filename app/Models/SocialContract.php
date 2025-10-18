<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'submission_date',
        'status',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(SocialContractRecord::class);
    }
}
