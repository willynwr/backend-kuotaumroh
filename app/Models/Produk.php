<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    protected $fillable = [
        'nama_paket',
        'tipe_paket',
        'provider',
        'masa_aktif',
        'total_kuota',
        'kuota_utama',
        'kuota_bonus',
        'telp',
        'sms',
        'harga_api',           // Nama kolom sebenarnya di DB (bukan harga_app)
        'harga_komersial',     // Harga untuk strikethrough
        'price_bulk',          // Harga untuk agent
        'price_customer',      // Harga untuk customer umum
        'persentase_marginstar',
        'marginstar',
        'poin',
        'source_name',
        'promo',
    ];

    protected $casts = [
        'masa_aktif' => 'integer',
        'total_kuota' => 'integer',
        'kuota_utama' => 'integer',
        'kuota_bonus' => 'integer',
        'telp' => 'integer',
        'sms' => 'integer',
        'harga_api' => 'integer',
        'harga_komersial' => 'integer',
        'price_bulk' => 'integer',
        'price_customer' => 'integer',
        'persentase_marginstar' => 'decimal:2',
        'marginstar' => 'integer',
        'poin' => 'integer',
    ];

    // Note: Tidak ada kolom is_active di table produk

    /**
     * Scope untuk filter produk umroh
     */
    public function scopeUmroh($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('source_name')
              ->orWhereIn('provider', ['TELKOMSEL', 'INDOSAT', 'XL', 'AXIS', 'TRI', 'BYU', 'SMARTFREN']);
        });
    }
}
