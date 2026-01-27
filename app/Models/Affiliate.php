<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Affiliate extends Model
{
    use HasFactory, HasApiTokens, HasCustomId;

    protected $table = 'affiliate';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the custom ID prefix for this model (AFT00001)
     */
    public static function getIdPrefix(): string
    {
        return 'AFT';
    }

    /**
     * Get the number of digits for the ID
     */
    public static function getIdDigits(): int
    {
        return 5;
    }

    protected $fillable = [
        'nama',
        'email',
        'no_wa',
        'provinsi',
        'kab_kota',
        'alamat_lengkap',
        'ktp',
        'date_register',
        'is_active',
        'link_referral',
        'ref_code',
    ];

    protected $casts = [
        'date_register' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Agent
     * Satu Affiliate bisa memiliki banyak Agent
     */
    public function agents()
    {
        return $this->hasMany(Agent::class, 'affiliate_id', 'id');
    }
}
