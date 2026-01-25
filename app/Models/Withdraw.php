<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'withdraw';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the custom ID prefix for this model (WDW000001)
     */
    public static function getIdPrefix(): string
    {
        return 'WDW';
    }

    /**
     * Get the number of digits for the ID
     */
    public static function getIdDigits(): int
    {
        return 6;
    }

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
