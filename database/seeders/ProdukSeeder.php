<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produk = [
            [
                'nama_paket' => 'Umroh Basic 15GB',
                'tipe_paket' => 'Internet',
                'provider' => 'TELKOMSEL',
                'masa_aktif' => 30,
                'total_kuota' => 15000,
                'kuota_utama' => 12000,
                'kuota_bonus' => 3000,
                'telp' => 100,
                'sms' => 50,
                'harga_modal' => 200000,
            ],
            [
                'nama_paket' => 'Premium 30GB',
                'tipe_paket' => 'Internet+Voice',
                'provider' => 'INDOSAT',
                'masa_aktif' => 30,
                'total_kuota' => 30000,
                'kuota_utama' => 25000,
                'kuota_bonus' => 5000,
                'telp' => 200,
                'sms' => 100,
                'harga_modal' => 350000,
            ],
            [
                'nama_paket' => 'Haji Ekonomis 10GB',
                'tipe_paket' => 'Internet',
                'provider' => 'TELKOMSEL',
                'masa_aktif' => 45,
                'total_kuota' => 10000,
                'kuota_utama' => 8000,
                'kuota_bonus' => 2000,
                'telp' => 80,
                'sms' => 40,
                'harga_modal' => 180000,
            ],
            [
                'nama_paket' => 'Super 50GB',
                'tipe_paket' => 'Internet+Voice',
                'provider' => 'XL',
                'masa_aktif' => 30,
                'total_kuota' => 50000,
                'kuota_utama' => 40000,
                'kuota_bonus' => 10000,
                'telp' => 300,
                'sms' => 150,
                'harga_modal' => 500000,
            ],
            [
                'nama_paket' => 'Unlimited',
                'tipe_paket' => 'Unlimited',
                'provider' => 'INDOSAT',
                'masa_aktif' => 30,
                'total_kuota' => 999999,
                'kuota_utama' => 999999,
                'kuota_bonus' => 0,
                'telp' => 500,
                'sms' => 200,
                'harga_modal' => 600000,
            ],
            [
                'nama_paket' => 'Express 20GB',
                'tipe_paket' => 'Internet',
                'provider' => 'TRI',
                'masa_aktif' => 15,
                'total_kuota' => 20000,
                'kuota_utama' => 18000,
                'kuota_bonus' => 2000,
                'telp' => 120,
                'sms' => 60,
                'harga_modal' => 250000,
            ],
            [
                'nama_paket' => 'Premium Plus 40GB',
                'tipe_paket' => 'Internet+Voice',
                'provider' => 'XL',
                'masa_aktif' => 30,
                'total_kuota' => 40000,
                'kuota_utama' => 35000,
                'kuota_bonus' => 5000,
                'telp' => 250,
                'sms' => 120,
                'harga_modal' => 420000,
            ],
            [
                'nama_paket' => 'Budget 8GB',
                'tipe_paket' => 'Internet',
                'provider' => 'TELKOMSEL',
                'masa_aktif' => 30,
                'total_kuota' => 8000,
                'kuota_utama' => 6000,
                'kuota_bonus' => 2000,
                'telp' => 60,
                'sms' => 30,
                'harga_modal' => 90000,
            ],
        ];

        DB::table('produk')->insert($produk);
    }
}
