<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'nama_paket',
        'tipe_paket',
        'masa_aktif',
        'total_kuota',
        'kuota_utama',
        'kuota_bonus',
        'telp',
        'sms',
        'harga_modal',
        'harga_eup',
        'persentase_margin_star',
        'margin_star',
        'margin_total',
        'fee_travel',
        'persentase_fee_travel',
        'persentase_fee_affiliate',
        'fee_affiliate',
        'persentase_fee_host',
        'fee_host',
        'harga_tp_travel',
        'harga_tp_host',
        'poin',
        'profit',
    ];

    protected $casts = [
        'masa_aktif' => 'integer',
        'total_kuota' => 'integer',
        'kuota_utama' => 'integer',
        'kuota_bonus' => 'integer',
        'telp' => 'integer',
        'sms' => 'integer',
        'harga_modal' => 'integer',
        'harga_eup' => 'integer',
        'persentase_margin_star' => 'decimal:2',
        'margin_star' => 'integer',
        'margin_total' => 'integer',
        'fee_travel' => 'integer',
        'persentase_fee_travel' => 'decimal:2',
        'persentase_fee_affiliate' => 'decimal:2',
        'fee_affiliate' => 'integer',
        'persentase_fee_host' => 'decimal:2',
        'fee_host' => 'integer',
        'harga_tp_travel' => 'integer',
        'harga_tp_host' => 'integer',
        'poin' => 'integer',
        'profit' => 'integer',
    ];
}
