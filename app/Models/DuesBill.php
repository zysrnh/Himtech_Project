<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuesBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'dues_period_id',
        'member_id',
        'amount',
        'status', // unpaid, pending, paid
        'is_on_time',
        'proof_path',
        'paid_at',
        'verified_by',
        'payment_method',
        'snap_token',
        'midtrans_order_id',
        'midtrans_payment_type',
        'payment_channel',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_on_time' => 'boolean',
            'paid_at' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function duesPeriod(): BelongsTo
    {
        return $this->belongsTo(DuesPeriod::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
