<?php

namespace App\Models;

use Database\Factories\MembershipTransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipTransaction extends Model
{
    /** @use HasFactory<MembershipTransactionFactory> */
    use HasFactory;

    protected $fillable = ['member_id', 'membership_level_id', 'amount', 'starts_at', 'expires_at'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'starts_at' => 'date', 'expires_at' => 'date'];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(MembershipLevel::class, 'membership_level_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
