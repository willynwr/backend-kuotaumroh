<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'rekening';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the custom ID prefix for this model (BAC000001)
     */
    public static function getIdPrefix(): string
    {
        return 'BAC';
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
