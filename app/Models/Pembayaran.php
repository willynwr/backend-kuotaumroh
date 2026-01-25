<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembayaran extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'pembayaran';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the custom ID prefix for this model (PAY0000001)
     */
    public static function getIdPrefix(): string
    {
        return 'PAY';
    }

    /**
     * Get the number of digits for the ID
     */
    public static function getIdDigits(): int
    {
        return 7;
    }

    /**
     * Status pembayaran constants - sesuai dengan nilai di database
     */
    const STATUS_WAITING = 'WAITING';
    const STATUS_VERIFY = 'VERIFY';
    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_FAILED = 'FAILED';
    const STATUS_EXPIRED = 'EXPIRED';

    // Backward compatibility aliases
    const STATUS_MENUNGGU = 'WAITING';
    const STATUS_VERIFIKASI = 'VERIFY';
    const STATUS_SELESAI = 'SUCCESS';
    const STATUS_GAGAL = 'FAILED';

    /**
     * Metode pembayaran constants
     */
    const METODE_QRIS = 'QRIS';
    const METODE_SALDO = 'SALDO';

    protected $fillable = [
        'batch_id',
        'agent_id',
        'produk_id',
        'nama_batch',
        'sub_total',
        'biaya_platform',
        'unique_code',        // untuk payment unique (integer di DB)
        'total_pembayaran',
        'profit',
        'metode_pembayaran',
        'bank',
        'no_rekening',
        'va',
        'qris_payload',       // untuk menyimpan QRIS data/detail
        'verification_ref',   // untuk RRN verifikasi
        'paid_at',           // waktu pembayaran sukses
        'expired_at',
        'status_pembayaran',
        'provider',
    ];

    protected $casts = [
        'sub_total' => 'integer',
        'biaya_platform' => 'integer',
        'unique_code' => 'integer',
        'total_pembayaran' => 'integer',
        'profit' => 'integer',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected $attributes = [
        'status_pembayaran' => self::STATUS_WAITING,
    ];

    /**
     * Relasi ke Pesanan (banyak pesanan dalam 1 batch)
     */
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'batch_id', 'batch_id');
    }

    /**
     * Relasi ke Agent
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Scope: Filter by ref_code from pesanan
     */
    public function scopeByRefCode($query, $refCode)
    {
        return $query->whereHas('pesanan', function ($q) use ($refCode) {
            $q->where('ref_code', $refCode);
        });
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_pembayaran', $status);
    }

    /**
     * Scope for pending payments (WAITING or VERIFY)
     */
    public function scopePending($query)
    {
        return $query->whereIn('status_pembayaran', [
            self::STATUS_WAITING, 
            self::STATUS_VERIFY
        ]);
    }

    /**
     * Scope for waiting payments
     */
    public function scopeWaiting($query)
    {
        return $query->where('status_pembayaran', self::STATUS_WAITING);
    }

    /**
     * Scope for successful payments
     */
    public function scopeSuccess($query)
    {
        return $query->where('status_pembayaran', self::STATUS_SUCCESS);
    }

    /**
     * Scope for expired payments
     */
    public function scopeExpired($query)
    {
        return $query->where('status_pembayaran', self::STATUS_EXPIRED);
    }

    /**
     * Scope: Filter hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Check if payment is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expired_at) {
            return false;
        }
        return now()->gt($this->expired_at);
    }

    /**
     * Check if payment is still valid (not expired)
     */
    public function isValid(): bool
    {
        return $this->expired_at && $this->expired_at->isFuture();
    }

    /**
     * Check if payment is still pending
     */
    public function isPending(): bool
    {
        return in_array($this->status_pembayaran, [
            self::STATUS_WAITING,
            self::STATUS_VERIFY
        ]);
    }

    /**
     * Check if payment can be verified
     */
    public function canVerify(): bool
    {
        return $this->status_pembayaran === self::STATUS_WAITING && $this->isValid();
    }

    /**
     * Mark as verifying
     */
    public function markAsVerifying(): bool
    {
        return $this->update(['status_pembayaran' => self::STATUS_VERIFY]);
    }

    /**
     * Mark as success
     */
    public function markAsSuccess(string $rrn = null, $date = null): bool
    {
        return $this->update([
            'status_pembayaran' => self::STATUS_SUCCESS,
            'verification_ref' => $rrn,
            'paid_at' => $date ?? now(),
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(): bool
    {
        return $this->update(['status_pembayaran' => self::STATUS_FAILED]);
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(): bool
    {
        return $this->update(['status_pembayaran' => self::STATUS_EXPIRED]);
    }

    /**
     * Get remaining time in seconds
     */
    public function getRemainingTimeAttribute(): int
    {
        if (!$this->expired_at) {
            return 0;
        }
        
        $diff = now()->diffInSeconds($this->expired_at, false);
        return max(0, $diff);
    }

    /**
     * Accessor untuk backward compatibility - payment_unique
     */
    public function getPaymentUniqueAttribute(): int
    {
        return $this->unique_code ?? 0;
    }

    /**
     * Mutator untuk backward compatibility - payment_unique
     */
    public function setPaymentUniqueAttribute($value)
    {
        $this->attributes['unique_code'] = $value;
    }

    /**
     * Accessor untuk backward compatibility - qris_rrn
     */
    public function getQrisRrnAttribute(): ?string
    {
        return $this->verification_ref;
    }

    /**
     * Mutator untuk backward compatibility - qris_rrn
     */
    public function setQrisRrnAttribute($value)
    {
        $this->attributes['verification_ref'] = $value;
    }

    /**
     * Accessor untuk backward compatibility - qris_date
     */
    public function getQrisDateAttribute()
    {
        return $this->paid_at;
    }

    /**
     * Mutator untuk backward compatibility - qris_date
     */
    public function setQrisDateAttribute($value)
    {
        $this->attributes['paid_at'] = $value;
    }

    /**
     * Accessor untuk backward compatibility - detail
     */
    public function getDetailAttribute()
    {
        return $this->qris_payload;
    }

    /**
     * Mutator untuk backward compatibility - detail
     */
    public function setDetailAttribute($value)
    {
        $this->attributes['qris_payload'] = is_array($value) ? json_encode($value) : $value;
    }
}
