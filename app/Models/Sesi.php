<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Sesi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelatihan_id',
        'judul_sesi',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'batas_terlambat_menit',
        'lokasi',
        'qr_token',
        'kode_manual',
        'token_expired_at',
        'prev_qr_token',
        'prev_token_expired_at',
        'is_finalized',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'token_expired_at' => 'datetime',
            'prev_token_expired_at' => 'datetime',
            'is_finalized' => 'boolean',
            'batas_terlambat_menit' => 'integer',
        ];
    }

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class);
    }

    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class, 'sesi_id');
    }

    /**
     * Start and End times as full Carbon instances
     */
    public function getWaktuMulaiAttribute(): Carbon
    {
        $jam = substr((string) $this->jam_mulai, 0, 5);
        return Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . $jam);
    }

    public function getWaktuSelesaiAttribute(): Carbon
    {
        $jam = substr((string) $this->jam_selesai, 0, 5);
        return Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . $jam);
    }

    public function getWindowBukaAttribute(): Carbon
    {
        return $this->waktu_mulai->copy()->subMinutes(15);
    }

    public function getBatasTerlambatTimeAttribute(): Carbon
    {
        return $this->waktu_mulai->copy()->addMinutes($this->batas_terlambat_menit ?? 15);
    }

    /**
     * Check if currently within attendance window (15m before start until end)
     */
    public function isWithinAttendanceWindow(?Carbon $now = null): bool
    {
        $now = $now ?? now();
        return $now->between($this->window_buka, $this->waktu_selesai);
    }

    public function isBeforeWindow(?Carbon $now = null): bool
    {
        $now = $now ?? now();
        return $now->lessThan($this->window_buka);
    }

    public function isAfterWindow(?Carbon $now = null): bool
    {
        $now = $now ?? now();
        return $now->greaterThan($this->waktu_selesai);
    }

    /**
     * Check if attendance at $now counts as late
     */
    public function isLate(?Carbon $now = null): bool
    {
        $now = $now ?? now();
        return $now->greaterThan($this->batas_terlambat_time);
    }

    /**
     * Rotate QR token and 6-char manual code every 5 minutes with 15s grace period
     */
    public function rotateTokenIfNeeded(bool $force = false): bool
    {
        $now = now();
        $isExpired = is_null($this->token_expired_at) || $now->greaterThanOrEqualTo($this->token_expired_at);

        if ($force || $isExpired) {
            // Keep previous token with 15-second grace period
            if ($this->qr_token) {
                $this->prev_qr_token = $this->qr_token;
                $this->prev_token_expired_at = $now->copy()->addSeconds(15);
            }

            // Generate new token and 6-char uppercase alphanumeric manual code
            $this->qr_token = Str::random(40);
            $this->kode_manual = strtoupper(Str::random(6));
            $this->token_expired_at = $now->copy()->addMinutes(5);

            $this->save();
            return true;
        }

        return false;
    }

    /**
     * Validate whether input matches current token or grace-period previous token or manual code
     */
    public function isTokenValid(string $input): bool
    {
        $now = now();
        $cleanInput = trim($input);

        // Check current token
        if ($this->qr_token && hash_equals($this->qr_token, $cleanInput)) {
            if ($this->token_expired_at && $now->lessThanOrEqualTo($this->token_expired_at)) {
                return true;
            }
        }

        // Check manual code
        if ($this->kode_manual && strtoupper($this->kode_manual) === strtoupper($cleanInput)) {
            if ($this->token_expired_at && $now->lessThanOrEqualTo($this->token_expired_at)) {
                return true;
            }
        }

        // Check grace-period previous token (15 seconds)
        if ($this->prev_qr_token && hash_equals($this->prev_qr_token, $cleanInput)) {
            if ($this->prev_token_expired_at && $now->lessThanOrEqualTo($this->prev_token_expired_at)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Remaining seconds of current token
     */
    public function remainingSeconds(): int
    {
        if (!$this->token_expired_at) {
            return 0;
        }
        return max(0, now()->diffInSeconds($this->token_expired_at, false));
    }
}
