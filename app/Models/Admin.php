<?php

namespace App\Models;

use App\Traits\HasCustomId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasFactory, HasApiTokens, HasCustomId;

    protected $table = 'admin';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the custom ID prefix for this model (ADM00001)
     */
    public static function getIdPrefix(): string
    {
        return 'ADM';
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
    ];
}
