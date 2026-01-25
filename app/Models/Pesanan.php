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
        'kategori_channel',
        'channel_id',
        'produk_id',
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
     * Relasi ke Agent (when kategori_channel = 'agent')
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'channel_id');
    }

    /**
     * Relasi ke Affiliate (when kategori_channel = 'affiliate')
     */
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'channel_id');
    }

    /**
     * Relasi ke Freelance (when kategori_channel = 'freelance')
     */
    public function freelance()
    {
        return $this->belongsTo(Freelance::class, 'channel_id');
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
     * Scope: Filter by agent (channel_id with kategori_channel = 'agent')
     */
    public function scopeByAgent($query, $agentId)
    {
        return $query->where('kategori_channel', 'agent')->where('channel_id', $agentId);
    }

    /**
     * Scope: Filter by affiliate
     */
    public function scopeByAffiliate($query, $affiliateId)
    {
        return $query->where('kategori_channel', 'affiliate')->where('channel_id', $affiliateId);
    }

    /**
     * Scope: Filter by freelance
     */
    public function scopeByFreelance($query, $freelanceId)
    {
        return $query->where('kategori_channel', 'freelance')->where('channel_id', $freelanceId);
    }

    /**
     * Scope: Filter by channel
     */
    public function scopeByChannel($query, $kategori, $channelId)
    {
        return $query->where('kategori_channel', $kategori)->where('channel_id', $channelId);
    }
}
