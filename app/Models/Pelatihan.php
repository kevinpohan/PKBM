<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelatihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'nama',
        'deskripsi',
        'kuota',
        'butuh_persetujuan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'kuota' => 'integer',
            'butuh_persetujuan' => 'boolean',
        ];
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function sesis(): HasMany
    {
        return $this->hasMany(Sesi::class, 'pelatihan_id')->orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc');
    }

    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'pelatihan_id');
    }

    public function pesertas(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'pendaftarans')
            ->withPivot(['id', 'status', 'tanggal_daftar'])
            ->withTimestamps();
    }

    public function pesertaDisetujui(): BelongsToMany
    {
        return $this->pesertas()->wherePivot('status', 'disetujui');
    }

    public function sisaKuota(): ?int
    {
        if (is_null($this->kuota)) {
            return null;
        }

        $terisi = $this->pesertas()->wherePivot('status', 'disetujui')->count();
        return max(0, $this->kuota - $terisi);
    }

    public function isPenuh(): bool
    {
        if (is_null($this->kuota)) {
            return false;
        }

        return ($this->sisaKuota() ?? 1) <= 0;
    }
}
