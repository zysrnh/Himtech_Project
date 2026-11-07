<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'avatar',
        'password',
        'is_super_admin',
        'admin_role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    /**
     * Cek apakah user adalah Super Admin (akses penuh platform).
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin && ($this->admin_role === 'super_admin' || empty($this->admin_role));
    }

    /**
     * Cek apakah user adalah Admin Penerima Organisasi (khusus review & ACC pengajuan).
     */
    public function isAdminPenerima(): bool
    {
        return $this->is_super_admin && $this->admin_role === 'admin_penerima';
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->withPivot(['role', 'member_id', 'is_active'])
            ->withTimestamps();
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    /**
     * Dapatkan URL foto profil avatar pengguna atau fallback avatar inisial resmi Kasly.
     */
    public function avatarUrl(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/'.$this->avatar);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=2F4862&background=DDEBFC&font-size=0.4&bold=true';
    }
}
