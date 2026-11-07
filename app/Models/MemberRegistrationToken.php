<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberRegistrationToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'token',
        'max_uses',
        'used_count',
        'expires_at',
        'created_by',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
            'max_uses' => 'integer',
            'used_count' => 'integer',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Cek apakah token sudah melewati batas waktu kedaluwarsa.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Cek apakah kuota pendaftaran sudah mencapai batas maksimal.
     */
    public function isQuotaFull(): bool
    {
        return $this->used_count >= $this->max_uses;
    }

    /**
     * Cek apakah token masih sah digunakan.
     */
    public function isValid(): bool
    {
        return $this->is_active && ! $this->isExpired() && ! $this->isQuotaFull();
    }

    /**
     * Hitung sisa kuota yang masih dapat digunakan.
     */
    public function remainingUses(): int
    {
        return max(0, $this->max_uses - $this->used_count);
    }
}
