<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freelance extends Model
{
    use HasFactory;

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
        return $this->hasMany(Agent::class);
    }
}
