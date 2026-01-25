<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory, HasCustomId;

    protected $table = 'reward';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the custom ID prefix for this model (RWD000001)
     */
    public static function getIdPrefix(): string
    {
        return 'RWD';
    }

    /**
     * Get the number of digits for the ID
     */
    public static function getIdDigits(): int
    {
        return 6;
    }

    protected $fillable = [
        'nama_reward',
        'poin',
        'stok',
        'is_active',
    ];

    protected $casts = [
        'poin' => 'integer',
        'stok' => 'integer',
        'is_active' => 'boolean',
    ];
}
