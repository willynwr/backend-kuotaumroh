<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'pesanan';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the custom ID prefix for this model (ORD0000001)
     */
    public static function getIdPrefix(): string
    {
        return 'ORD';
    }

    /**
     * Get the number of digits for the ID
     */
    public static function getIdDigits(): int
    {
        return 7;
    }

    protected $fillable = [
        'batch_id',
        'agent_id',
        'produk_id',
        'package_id',
        'nama_batch',
        'msisdn',
        'ref_code',
        'detail',
        'nama_paket',
        'tipe_paket',
        'masa_aktif',
        'total_kuota',
        'kuota_utama',
        'kuota_bonus',
        'telp',
        'sms',
        'harga_modal',
        'harga_jual',
        'profit',
        'jadwal_aktivasi',
        'status_aktivasi',
        'card_type',
        'product_id_digipos',
        'menu_id_digipos',
        'id_digipos',
        'device_id',
        'ip_address',
    ];

    protected $casts = [
        'masa_aktif' => 'integer',
        'total_kuota' => 'integer',
        'kuota_utama' => 'integer',
        'kuota_bonus' => 'integer',
        'telp' => 'boolean',
        'sms' => 'boolean',
        'harga_modal' => 'integer',
        'harga_jual' => 'integer',
        'profit' => 'integer',
        'jadwal_aktivasi' => 'datetime',
    ];

    /**
     * Status aktivasi constants
     */
    const STATUS_PROSES = 'proses';
    const STATUS_BERHASIL = 'berhasil';
    const STATUS_GAGAL = 'gagal';

    /**
     * Relasi ke Pembayaran
     */
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'batch_id', 'batch_id');
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
     * Scope: Filter by batch_id
     */
    public function scopeByBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    /**
     * Scope: Filter by ref_code (agent)
     */
    public function scopeByRefCode($query, $refCode)
    {
        return $query->where('ref_code', $refCode);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_aktivasi', $status);
    }

    /**
     * Scope: Filter by agent_id
     */
    public function scopeByAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }
}
