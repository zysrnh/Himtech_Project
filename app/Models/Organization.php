<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot(['role', 'member_id', 'is_active'])
            ->withTimestamps();
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function cashCategories(): HasMany
    {
        return $this->hasMany(CashCategory::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function duesPeriods(): HasMany
    {
        return $this->hasMany(DuesPeriod::class);
    }

    public function duesBills(): HasMany
    {
        return $this->hasMany(DuesBill::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function calculateBalance(): float
    {
        $in = (float) $this->transactions()->where('type', 'in')->sum('amount');
        $out = (float) $this->transactions()->where('type', 'out')->sum('amount');

        return $in - $out;
    }
}
