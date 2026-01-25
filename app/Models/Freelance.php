<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Freelance extends Model
{
    use HasFactory, HasApiTokens, HasCustomId;

    protected $table = 'freelance';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the custom ID prefix for this model (FRL00001)
     */
    public static function getIdPrefix(): string
    {
        return 'FRL';
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
     * Satu Freelance bisa memiliki banyak Agent
     */
    public function agents()
    {
        return $this->hasMany(Agent::class, 'freelance_id', 'id');
    }
}
