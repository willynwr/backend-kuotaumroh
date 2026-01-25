<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Agent extends Model
{
    use HasFactory, HasApiTokens, HasCustomId;

    protected $table = 'agent';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the custom ID prefix for this model (AGT00001)
     */
    public static function getIdPrefix(): string
    {
        return 'AGT';
    }

    /**
     * Get the number of digits for the ID
     */
    public static function getIdDigits(): int
    {
        return 5;
    }

    protected $fillable = [
        'email',
        'affiliate_id',
        'freelance_id',
        'kategori_agent',
        'nama_pic',
        'no_hp',
        'nama_travel',
        'jenis_travel',
        'total_traveller',
        'provinsi',
        'kabupaten_kota',
        'alamat_lengkap',
        'link_gmaps',
        'long',
        'lat',
        'link_referal',
        'date_approve',
        'logo',
        'surat_ppiu',
        'saldo',
        'saldo_bulan',
        'saldo_tahun',
        'status',
        'is_active',
    ];

    /**
     * Relasi ke Affiliate
     * Agent bisa berasal dari Affiliate
     */
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Relasi ke Freelance
     * Agent bisa berasal dari Freelance
     */
    public function freelance()
    {
        return $this->belongsTo(Freelance::class);
    }

    /**
     * Relasi ke Rekening
     * Agent bisa memiliki banyak Rekening
     */
    public function rekenings()
    {
        return $this->hasMany(Rekening::class);
    }

    /**
     * Relasi ke Withdraw
     * Agent bisa memiliki banyak Withdraw
     */
    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }
}
