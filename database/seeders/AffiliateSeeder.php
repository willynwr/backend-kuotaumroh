<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AffiliateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $affiliates = [
            [
                'nama' => 'Afif Karomi',
                'email' => 'afifkaromi264@gmail.com',
                'no_wa' => '081234567890',
                'provinsi' => 'Jawa Barat',
                'kab_kota' => 'Bandung',
                'alamat_lengkap' => 'Jl. Merdeka No. 123, Bandung, Jawa Barat 40111',
                'date_register' => Carbon::now()->format('Y-m-d'),
                'is_active' => true,
                'link_referral' => 'afif-karomi',
                'ref_code' => 'AFF-AFIF',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'Badi Santoso',
                'email' => 'badi.affiliate@gmail.com',
                'no_wa' => '081234567911',
                'provinsi' => 'DKI Jakarta',
                'kab_kota' => 'Jakarta Barat',
                'alamat_lengkap' => 'Jl. Hayam Wuruk No. 45, Jakarta Barat 11160',
                'date_register' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'is_active' => true,
                'link_referral' => 'badi-santoso',
                'ref_code' => 'AFF-BADI',
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(20),
            ],
            [
                'nama' => 'Citra Dewi',
                'email' => 'citra.affiliate@gmail.com',
                'no_wa' => '081234567912',
                'provinsi' => 'Jawa Timur',
                'kab_kota' => 'Gresik',
                'alamat_lengkap' => 'Jl. Sukarno Hatta No. 78, Gresik, Jawa Timur 61121',
                'date_register' => Carbon::now()->subDays(18)->format('Y-m-d'),
                'is_active' => true,
                'link_referral' => 'citra-dewi',
                'ref_code' => 'AFF-CITRA',
                'created_at' => Carbon::now()->subDays(18),
                'updated_at' => Carbon::now()->subDays(18),
            ],
            [
                'nama' => 'Dina Kusuma',
                'email' => 'dina.affiliate@gmail.com',
                'no_wa' => '081234567913',
                'provinsi' => 'Jawa Tengah',
                'kab_kota' => 'Semarang',
                'alamat_lengkap' => 'Jl. Imam Bonjol No. 12, Semarang, Jawa Tengah 50131',
                'date_register' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'is_active' => true,
                'link_referral' => 'dina-kusuma',
                'ref_code' => 'AFF-DINA',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'nama' => 'Eva Pratama',
                'email' => 'eva.affiliate@gmail.com',
                'no_wa' => '081234567914',
                'provinsi' => 'Bali',
                'kab_kota' => 'Denpasar',
                'alamat_lengkap' => 'Jl. By Pass Ngurah Rai No. 99, Denpasar, Bali 80361',
                'date_register' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'is_active' => true,
                'link_referral' => 'eva-pratama',
                'ref_code' => 'AFF-EVA',
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(12),
            ],
            [
                'nama' => 'Fina Putri',
                'email' => 'fina.affiliate@gmail.com',
                'no_wa' => '081234567915',
                'provinsi' => 'Yogyakarta',
                'kab_kota' => 'Yogyakarta',
                'alamat_lengkap' => 'Jl. Kusumanegara No. 56, Yogyakarta 55165',
                'date_register' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'is_active' => true,
                'link_referral' => 'fina-putri',
                'ref_code' => 'AFF-FINA',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'nama' => 'Gita Handari',
                'email' => 'gita.affiliate@gmail.com',
                'no_wa' => '081234567916',
                'provinsi' => 'Sumatera Utara',
                'kab_kota' => 'Medan',
                'alamat_lengkap' => 'Jl. Sudirman No. 21, Medan, Sumatera Utara 20111',
                'date_register' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'is_active' => true,
                'link_referral' => 'gita-handari',
                'ref_code' => 'AFF-GITA',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'nama' => 'Haris Wijaya',
                'email' => 'haris.affiliate@gmail.com',
                'no_wa' => '081234567917',
                'provinsi' => 'Kalimantan Timur',
                'kab_kota' => 'Balikpapan',
                'alamat_lengkap' => 'Jl. Jenderal Ahmad Yani No. 34, Balikpapan, Kalimantan Timur 76112',
                'date_register' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'is_active' => true,
                'link_referral' => 'haris-wijaya',
                'ref_code' => 'AFF-HARIS',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'nama' => 'Ira Mulyani',
                'email' => 'ira.affiliate@gmail.com',
                'no_wa' => '081234567918',
                'provinsi' => 'Sulawesi Selatan',
                'kab_kota' => 'Makassar',
                'alamat_lengkap' => 'Jl. Ahmad Yani No. 88, Makassar, Sulawesi Selatan 90232',
                'date_register' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'is_active' => true,
                'link_referral' => 'ira-mulyani',
                'ref_code' => 'AFF-IRA',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'nama' => 'Jaka Sembung',
                'email' => 'jaka.affiliate@gmail.com',
                'no_wa' => '081234567919',
                'provinsi' => 'Lampung',
                'kab_kota' => 'Bandar Lampung',
                'alamat_lengkap' => 'Jl. Raden Intan No. 67, Bandar Lampung, Lampung 35224',
                'date_register' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'is_active' => true,
                'link_referral' => 'jaka-sembung',
                'ref_code' => 'AFF-JAKA',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
        ];

        DB::table('affiliates')->insert($affiliates);
    }
}
