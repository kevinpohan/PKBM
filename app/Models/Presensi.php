<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'sesi_id',
        'user_id',
        'status',
        'waktu_server',
        'metode',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'waktu_server' => 'datetime',
        ];
    }

    public function sesi(): BelongsTo
    {
        return $this->belongsTo(Sesi::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'presensi_id')->latest('created_at');
    }
}
