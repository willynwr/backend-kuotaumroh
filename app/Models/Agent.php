<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'jenis_agent',
        'nama_pic',
        'no_hp',
        'nama_travel',
        'jenis_travel',
        'total_traveller',
        'provinsi',
        'kabupaten_kota',
        'alamat_lengkap',
        'link_gmaps',
        'long',
        'lat',
        'link_referal',
        'rekening_agent',
        'date_approve',
        'logo',
        'surat_ppiu',
        'status',
        'is_active',
    ];
}
