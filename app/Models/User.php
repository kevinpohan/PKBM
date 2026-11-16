<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'no_hp',
        'nomor_induk',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isTutor(): bool
    {
        return $this->role === 'tutor';
    }

    public function isPeserta(): bool
    {
        return $this->role === 'peserta';
    }

    // Tutor relationship
    public function pelatihans(): HasMany
    {
        return $this->hasMany(Pelatihan::class, 'tutor_id');
    }

    // Peserta relationship
    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'user_id');
    }

    public function pelatihanDiikuti(): BelongsToMany
    {
        return $this->belongsToMany(Pelatihan::class, 'pendaftarans')
            ->withPivot(['status', 'tanggal_daftar'])
            ->withTimestamps();
    }

    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class, 'user_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'pelaku_id');
    }
}
