<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class OrganizationPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'type',
        'channel_name',
        'account_number',
        'account_holder',
        'qris_image_path',
        'instructions',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Dapatkan URL publik dari gambar QRIS statis organisasi.
     */
    public function qrisUrl(): ?string
    {
        if ($this->qris_image_path && Storage::disk('public')->exists($this->qris_image_path)) {
            return asset('storage/'.$this->qris_image_path);
        }

        return null;
    }
}
