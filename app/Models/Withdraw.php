<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $table = 'withdraw';

    protected $fillable = [
        'agent_id',
        'rekening_id',
        'jumlah',
        'keterangan',
        'alasan_reject',
        'status',
        'date_approve',
    ];

    /**
     * Relasi ke Agent
     * Withdraw dimiliki oleh satu Agent
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Relasi ke Rekening
     * Withdraw menggunakan satu Rekening
     */
    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }
}
