<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;

    protected $table = 'rekening';

    protected $fillable = [
        'agent_id',
        'nama_rekening',
        'bank',
        'nomor_rekening',
    ];

    /**
     * Relasi ke Agent
     * Rekening dimiliki oleh satu Agent
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Relasi ke Withdraw
     * Rekening bisa digunakan untuk banyak Withdraw
     */
    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }
}
